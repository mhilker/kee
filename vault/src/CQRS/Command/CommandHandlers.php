<?php

declare(strict_types=1);

namespace App\CQRS\Command;

use App\CQRS\Command\Exception\CommandHandlerNotFoundException;
use App\CQRS\Command\Exception\InvalidCommandClassException;

final class CommandHandlers
{
    private array $handlers = [];

    public function __construct(array $handlers)
    {
        foreach ($handlers as $commandTopic => $commandHandler) {
            $this->add($commandTopic, $commandHandler);
        }
    }

    /**
     * @throws InvalidCommandClassException
     */
    private function add(string $commandTopic, callable $commandHandler): void
    {
        $this->handlers[$commandTopic] = $commandHandler;
    }

    public function has(string $commandTopic): bool
    {
        return isset($this->handlers[$commandTopic]);
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
    public function getHandlerForCommand(string $commandTopic): callable
    {
        if (!$this->has($commandTopic)) {
            throw new CommandHandlerNotFoundException('Could not find handler for command');
        }

        return $this->handlers[$commandTopic];
    }
}
