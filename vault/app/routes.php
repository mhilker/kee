<?php
declare(strict_types=1);

use App\Application\Actions\CommandAction;
use App\Application\Actions\EntryDetailAction;
use App\Application\Actions\EntryListAction;
use App\Application\Actions\EntryListHistoryAction;
use App\Application\Actions\GraphListAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return static function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->put('/command/{id}/{name}', CommandAction::class);

    $app->group('/graph', function (Group $group) {
        $group->get('', GraphListAction::class);
    });

    $app->group('/entry', function (Group $group) {
        $group->get('', EntryListAction::class);
        $group->get('/{id}', EntryDetailAction::class);
        $group->get('/{id}/history', EntryListHistoryAction::class);
    });
};
