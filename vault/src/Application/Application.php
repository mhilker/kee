<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Response\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

final class Application
{
    public static function run(): void
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();

        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        if (false) { // Should be set to true in production
            $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
        }

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../app/dependencies.php';
        $dependencies($containerBuilder);

        // Set up repositories
        $repositories = require __DIR__ . '/../../app/repositories.php';
        $repositories($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $callableResolver = $app->getCallableResolver();

        // Register middleware
        $middleware = require __DIR__ . '/../../app/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../../app/routes.php';
        $routes($app);

        // Create Request object from globals
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        // Add Routing Middleware
        $app->addRoutingMiddleware();

        // Add Error Middleware
//        $app->addErrorMiddleware(true, true, true);

        // Run App & Emit Response
        $response = $app->handle($request);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }
}
