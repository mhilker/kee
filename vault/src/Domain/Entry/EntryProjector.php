<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\Event\EventHandler;
use App\CQRS\Event\Message;
use App\CQRS\ID\UUID;

final class EntryProjector implements EventHandler
{
    private EntryProjection $projection;

    public function __construct(EntryProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Message $message): void
    {
        match ($message->getEvent()->getTopic()) {
            EntryCreatedEvent::TOPIC => $this->handleEntryCreated($message->getEvent()),
        };
    }

    private function handleEntryCreated(EntryCreatedEvent $event): void
    {
        $parent = UUID::fromV4('638862cf-13a1-4d7b-8c87-32c65d42f367');
        $this->projection->addEntry($event->getId(), $parent, $event->getTitle(), $event->getUrl(), $event->getUsername(), $event->getPassword());
    }
}
