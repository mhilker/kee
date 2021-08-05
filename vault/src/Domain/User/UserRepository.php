<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\ID;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @throws UserNotFoundException
     */
    public function findUserOfId(ID $id): User;
}
