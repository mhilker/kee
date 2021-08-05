<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $logger = new Logger('vault');

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler('php://stdout', Logger::DEBUG);
            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
