<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Middleware\BodyParsingMiddleware;

return static function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(BodyParsingMiddleware::class);
};
