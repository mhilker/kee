<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\Event\EventHandler;
use App\CQRS\Event\Message;
use App\CQRS\ID\UUID;

final class EntryHistoryProjector implements EventHandler
{
    private EntryHistoryProjection $projection;

    public function __construct(EntryHistoryProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Message $message): void
    {
        match ($message->getEvent()->getTopic()) {
            EntryCreatedEvent::TOPIC => $this->handleEntryCreated($message->getEvent(), $message),
        };
    }

    public function handleEntryCreated(EntryCreatedEvent $event, Message $message): void
    {
        $userId = UUID::fromV4('26f2b5e4-8289-402f-a769-a8c13c71f9fd');
        $this->projection->addEntry($event->getId(), $userId, $message->getOccurredOn(), EntryCreatedEvent::TOPIC, json_encode($event->getPayload(), JSON_THROW_ON_ERROR));
    }
}
