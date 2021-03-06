<?php

declare(strict_types=1);

namespace App\CQRS\Command;

use App\CQRS\Command\Exception\CommandFailedException;
use App\CQRS\Command\Exception\CommandHandlerNotFoundException;
use App\CQRS\Event\EventDispatcher;
use App\CQRS\EventStore\EventContext;
use Exception;

final class DirectCommandBus implements CommandBus
{
    private CommandHandlers $commandHandlers;
    private EventContext $context;
    private CommandPublisher $publisher;
    private EventDispatcher $dispatcher;

    public function __construct(
        CommandHandlers $commandHandlers,
        EventContext $context,
        CommandPublisher $publisher,
        EventDispatcher $dispatcher
    ) {
        $this->commandHandlers = $commandHandlers;
        $this->context = $context;
        $this->publisher = $publisher;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws CommandFailedException
     */
    public function execute(Command $command): void
    {
        $this->publisher->publish($command);

        while (!$this->publisher->isEmpty()) {
            $command = $this->publisher->dequeue();
            try {
                $this->doExecute($command);
            } catch (Exception $exception) {
                throw new CommandFailedException('Command failed', 0, $exception);
            }
        }
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
    private function doExecute(Command $command): void
    {
        $this->context->setCurrentCorrelationId($command->getId());
        $this->context->setCurrentCausationId($command->getId());

        $commandClass = $command->getTopic();
        $handler = $this->commandHandlers->getHandlerForCommand($commandClass);
        $handler($command);

        $this->dispatcher->dispatch();
    }
}
