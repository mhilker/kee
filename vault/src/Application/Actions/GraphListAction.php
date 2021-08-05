<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class GraphListAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return JsonResponse::build($response, 200, [
            [
                'id' => '544d56a2-f5c8-11eb-8374-67b007a504f1',
                'title' => 'A',
                'entries' => 0,
                'children' => [
                    [
                        'id' => 'a9578f8c-f5c8-11eb-a5bf-8f546fa6245a',
                        'title' => 'A.A',
                        'entries' => 0,
                        'children' => [],
                    ],
                    [
                        'id' => 'ac54d55a-f5c8-11eb-b325-8b47179cef96',
                        'title' => 'A.B',
                        'entries' => 0,
                        'children' => [
                            [
                                'id' => 'ba7f0ff6-f5c8-11eb-a4ae-b784ebd0b2b2',
                                'title' => 'A.B.A',
                                'entries' => 0,
                                'children' => [],
                            ],
                            [
                                'id' => 'bcdc75ae-f5c8-11eb-b0c8-2f900ac74284',
                                'title' => 'A.B.B',
                                'entries' => 0,
                                'children' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 'aeae6db6-f5c8-11eb-8da6-f705e3543eb1',
                        'title' => 'A.C',
                        'entries' => 0,
                        'children' => [],
                    ],
                ],
            ],
            [
                'id' => '5c7bff2c-f5c8-11eb-abef-e362317edb78',
                'title' => 'B',
                'entries' => 0,
                'children' => [],
            ],
            [
                'id' => '5f1ae400-f5c8-11eb-8e5a-9332fcf31fcf',
                'title' => 'A',
                'entries' => 0,
                'children' => [],
            ],
        ]);
    }
}
