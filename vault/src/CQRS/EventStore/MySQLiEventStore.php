<?php

declare(strict_types=1);

namespace App\CQRS\EventStore;

use App\CQRS\Event\Messages;
use App\CQRS\EventStore\Exception\EventStoreException;
use App\CQRS\ID\Identifier;
use Error;
use Exception;
use mysqli;

final class MySQLiEventStore implements EventStore
{
    private mysqli $connection;
    private EventMap $map;
    private EventContext $context;

    public function __construct(mysqli $connection, EventMap $map, EventContext $context)
    {
        $this->connection = $connection;
        $this->map = $map;
        $this->context = $context;
    }

    /**
     * @throws EventStoreException
     */
    public function store(Messages $messages): void
    {
        try {
            $this->connection->begin_transaction();

            $sql = <<<QUERY
                INSERT INTO `events` (
                    `event_id`, 
                    `correlation_id`, 
                    `causation_id`, 
                    `event_stream_id`, 
                    `event_stream_version`, 
                    `occurred_on`, 
                    `topic`, 
                    `version`, 
                    `payload`
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
            QUERY;

            $statement = $this->connection->prepare($sql);

            foreach ($messages as $message) {
                $params = [
                    $message->getId()->asString(),
                    $this->context->getCurrentCorrelationId()->asString(),
                    $this->context->getCurrentCausationId()->asString(),
                    $message->getEventStreamId()->asString(),
                    $message->getEventStreamVersion(),
                    $message->getOccurredOn()->format('Y-m-d H:i:s'),
                    $message->getEvent()->getTopic(),
                    $message->getEvent()->getVersion(),
                    json_encode($message->getEvent()->getPayload(), JSON_THROW_ON_ERROR),
                ];
                $statement->bind_param('ssssissss', ...$params);
                $statement->execute();
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw new EventStoreException('Could not store events', 0, $exception);
        }
    }

    /**
     * @throws EventStoreException
     */
    public function load(Identifier $id): Messages
    {
        $query = <<<QUERY
            SELECT 
                `event_id`,
                `correlation_id`,
                `causation_id`,
                `event_stream_id`,
                `event_stream_version`,
                `occurred_on`,
                `topic`,
                `version`,
                `payload`
            FROM 
                `events` 
            WHERE 
                `event_stream_id` = ?;
        QUERY;

        $messages = [];

        try {
            $statement = $this->connection->prepare($query);
            $params = [$id->asString()];
            $statement->bind_param('s', ...$params);
            $statement->bind_result(
                $event_id,
                $correlation_id,
                $causation_id,
                $event_stream_id,
                $event_stream_version,
                $occurred_on,
                $topic,
                $version,
                $payload
            );
            $statement->execute();

            while ($statement->fetch()) {
                $messages[] = $this->map->reconstitute([
                    'event_id' => $event_id,
                    'correlation_id' => $correlation_id,
                    'causation_id' => $causation_id,
                    'event_stream_id' => $event_stream_id,
                    'event_stream_version' => $event_stream_version,
                    'occurred_on' => $occurred_on,
                    'topic' => $topic,
                    'version' => $version,
                    'payload' => $payload,
                ]);
            }
        } catch (Exception $exception) {
            throw new EventStoreException('Could not load events', 0, $exception);
        }

        if (count($messages) === 0) {
            throw new EventStoreException('No events for event stream found');
        }

        return Messages::from($messages);
    }
}
