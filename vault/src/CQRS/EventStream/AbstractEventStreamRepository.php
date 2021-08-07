<?php

declare(strict_types=1);

namespace App\CQRS\EventStream;

use App\CQRS\EventStream\Exception\EventStreamNotFoundException;
use App\CQRS\EventStream\Exception\EventStreamNotSavedException;
use App\CQRS\Event\EventPublisher;
use App\CQRS\Event\Messages;
use App\CQRS\EventStore\EventStore;
use App\CQRS\ID\Identifier;
use Exception;

abstract class AbstractEventStreamRepository implements EventStreamRepository
{
    private EventStore $store;
    private EventPublisher $publisher;

    public function __construct(EventStore $store, EventPublisher $publisher)
    {
        $this->store = $store;
        $this->publisher = $publisher;
    }

    /**
     * @throws EventStreamNotSavedException
     */
    public function save(AbstractEventStream $stream): void
    {
        try {
            $messages = $stream->popMessages();
            $this->store->store($messages);
        } catch (Exception $exception) {
            throw new EventStreamNotSavedException('Could not save event stream', 0, $exception);
        }

        $this->publisher->publish($messages);
    }

    /**
     * @throws EventStreamNotFoundException
     */
    public function load(Identifier $id): AbstractEventStream
    {
        try {
            $messages = $this->store->load($id);
            return $this->createStreamWithMessages($messages);
        } catch (Exception $exception) {
            throw new EventStreamNotFoundException('Could not load event stream', 0, $exception);
        }
    }

    abstract protected function createStreamWithMessages(Messages $messages): AbstractEventStream;
}
