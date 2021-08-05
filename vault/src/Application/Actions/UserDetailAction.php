<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Response\JsonResponse;
use App\Domain\ID;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UserDetailAction
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = ID::fromString($args['id']);
        $user = $this->users->findUserOfId($userId);

        return JsonResponse::build($response, 200, $user);
    }
}
