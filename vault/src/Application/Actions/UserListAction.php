<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Response\JsonResponse;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UserListAction
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $users = $this->users->findAll();

        return JsonResponse::build($response, 200, $users);
    }
}
