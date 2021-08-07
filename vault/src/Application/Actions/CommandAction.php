<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Response\JsonResponse;
use App\CQRS\Command\CommandBus;
use App\CQRS\ID\UUID;
use App\Domain\Entry\CreateEntryCommand;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CommandAction
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $command = match ($args['name']) {
            CreateEntryCommand::TOPIC => new CreateEntryCommand(UUID::fromV4($args['id']), $body['title'], $body['url'], $body['username'], $body['password']),
        };

        $this->commandBus->execute($command);

        return JsonResponse::build($response, 200, []);
    }
}
