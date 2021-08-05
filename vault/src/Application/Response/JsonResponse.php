<?php

declare(strict_types=1);

namespace App\Application\Response;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

final class JsonResponse
{
    public static function build(ResponseInterface $response, int $statusCode, array|JsonSerializable $data): ResponseInterface
    {
        $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
