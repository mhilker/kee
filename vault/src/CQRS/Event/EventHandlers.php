<?php

declare(strict_types=1);

namespace App\CQRS\Event;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class EventHandlers implements IteratorAggregate
{
    private array $handlers = [];

    private function __construct(iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $this->add($handler);
        }
    }

    public static function from(iterable $handlers = []): EventHandlers
    {
        return new self($handlers);
    }

    private function add(EventHandler $eventHandler): void
    {
        $this->handlers[] = $eventHandler;
    }

    /**
     * @return Traversable | EventHandler[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->handlers);
    }
}
