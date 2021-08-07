<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\ID\Identifier;
use Exception;
use mysqli;

final class EntryHistoryProjection
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function addEntry(Identifier $id, Identifier $userId, \DateTimeImmutable $occurredOn, string $operation, string $newValues): void
    {
        try {
            $this->connection->begin_transaction();

            $sql = <<<QUERY
                INSERT INTO `projection_entry_history` (
                    `entry_id`,
                    `user_id`,
                    `occurred_on`,
                    `operation`, 
                    `new_values` 
                ) 
                VALUES (?, ?, ?, ?, ?);
            QUERY;

            $statement = $this->connection->prepare($sql);
            $params = [
                $id->asString(),
                $userId->asString(),
                $occurredOn->format('Y-m-d H:i:s'),
                $operation,
                $newValues,
            ];
            $statement->bind_param('sssss', ...$params);
            $statement->execute();

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw new Exception('Could not save entry history', 0, $exception);
        }
    }
}
