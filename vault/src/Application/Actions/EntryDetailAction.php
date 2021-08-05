<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class EntryDetailAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return JsonResponse::build($response, 200, []);
    }
}
