<?php

declare(strict_types=1);

namespace App\CQRS\EventStream;

use App\CQRS\Event\Event;
use App\CQRS\Event\Message;
use App\CQRS\Event\Messages;
use App\CQRS\ID\Identifier;

abstract class AbstractEventStream
{
    private int $version = 0;
    private array $messages = [];

    protected function __construct(?Messages $messages)
    {
        if ($messages !== null) {
            foreach ($messages as $message) {
                $this->apply($message->getEvent());
            }
        }
    }

    public static function from(Messages $messages): self
    {
        return new static($messages);
    }

    protected function record(Event $event): void
    {
        $this->apply($event);
        $this->messages[] = Message::wrap($this->getEventStreamId(), $this->version, $event);
    }

    private function apply(Event $event): void
    {
        $this->version++;
        $this->dispatch($event);
    }

    abstract protected function dispatch(Event $event): void;

    abstract protected function getEventStreamId(): Identifier;

    public function popMessages(): Messages
    {
        $messages = Messages::from($this->messages);
        $this->messages = [];
        return $messages;
    }
}
