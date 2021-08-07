<?php
declare(strict_types=1);

use App\CQRS\Command\CommandBus;
use App\CQRS\Command\CommandHandlers;
use App\CQRS\Command\CommandPublisher;
use App\CQRS\Command\DirectCommandBus;
use App\CQRS\Command\MemoryCommandPublisher;
use App\CQRS\Event\DirectEventBus;
use App\CQRS\Event\EventDispatcher;
use App\CQRS\Event\EventHandlers;
use App\CQRS\Event\EventPublisher;
use App\CQRS\Event\MemoryEventPublisher;
use App\CQRS\EventStore\DefaultEventMap;
use App\CQRS\EventStore\EventContext;
use App\CQRS\EventStore\EventMap;
use App\CQRS\EventStore\EventStore;
use App\CQRS\EventStore\MySQLiEventStore;
use App\Domain\Entry\CreateEntryCommand;
use App\Domain\Entry\CreateEntryCommandHandler;
use App\Domain\Entry\EntryCreatedEvent;
use App\Domain\Entry\EntryHistoryProjector;
use App\Domain\Entry\EntryProjector;
use App\Domain\Entry\EntryRepository;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $container) {
            $logger = new Logger('vault');

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler('php://stdout', Logger::DEBUG);
            $logger->pushHandler($handler);

            return $logger;
        },
        CommandBus::class => function (ContainerInterface $container) {
            return new DirectCommandBus(
                $container->get(CommandHandlers::class),
                $container->get(EventContext::class),
                $container->get(CommandPublisher::class),
                $container->get(EventDispatcher::class),
            );
        },
        CommandHandlers::class => function (ContainerInterface $container) {
            return new CommandHandlers([
                CreateEntryCommand::TOPIC => $container->get(CreateEntryCommandHandler::class),
            ]);
        },
        EventContext::class => function (ContainerInterface $container) {
            return new EventContext();
        },
        CommandPublisher::class => function (ContainerInterface $container) {
            return new MemoryCommandPublisher();
        },
        EventDispatcher::class => function (ContainerInterface $container) {
            return new DirectEventBus(
                $container->get(EventHandlers::class),
                $container->get(EventContext::class),
                $container->get(EventPublisher::class),
            );
        },
        EventHandlers::class => function (ContainerInterface $container) {
            return EventHandlers::from([
                $container->get(EntryProjector::class),
                $container->get(EntryHistoryProjector::class),
            ]);
        },
        EventPublisher::class => function (ContainerInterface $container) {
            return new MemoryEventPublisher();
        },
        EntryRepository::class => function (ContainerInterface $container) {
            return new EntryRepository(
                $container->get(EventStore::class),
                $container->get(EventPublisher::class),
            );
        },
        EventStore::class => function (ContainerInterface $container) {
            return new MySQLiEventStore(
                $container->get(mysqli::class),
                $container->get(EventMap::class),
                $container->get(EventContext::class),
            );
        },
        EventMap::class => function (ContainerInterface $container) {
            return new DefaultEventMap([
                EntryCreatedEvent::TOPIC => [
                    EntryCreatedEvent::VERSION => EntryCreatedEvent::class,
                ],
            ]);
        },
        mysqli::class => function (ContainerInterface $container) {
            $connection = new mysqli(
                getenv('MYSQL_HOST'),
                getenv('MYSQL_USERNAME'),
                getenv('MYSQL_PASSWORD'),
                getenv('MYSQL_DATABASE'),
                (int) getenv('MYSQL_PORT'),
            );
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            return $connection;
        }
    ]);
};
