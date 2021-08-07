<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\Event\Event;
use App\CQRS\EventStream\AbstractEventStream;
use App\CQRS\ID\Identifier;

final class EntryEventStream extends AbstractEventStream
{
    private Identifier $id;
    private string $title;
    private string $url;
    private string $username;
    private string $password;

    public static function create(Identifier $id, string $title, string $url, string $username, string $password): self
    {
        $entry = new self(null);
        $entry->record(new EntryCreatedEvent($id, $title, $url, $username, $password));
        return $entry;
    }

    protected function dispatch(Event $event): void
    {
        match ($event->getTopic()) {
            EntryCreatedEvent::TOPIC => $this->onCreated($event),
        };
    }

    private function onCreated(EntryCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->url = $event->getUrl();
        $this->username = $event->getUsername();
        $this->password = $event->getPassword();
    }

    protected function getEventStreamId(): Identifier
    {
        return $this->id;
    }
}
