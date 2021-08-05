<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\ID;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    private array $users;

    public function __construct(array $users = null)
    {
        $this->users = $users ?? [
            new User('e2cf5a48-f5c7-11eb-83b9-af62e4cb5219', 'bill.gates', 'Bill', 'Gates'),
            new User('1e1bf5fc-f5c8-11eb-9b3d-cbc85056afe1', 'steve.jobs', 'Steve', 'Jobs'),
            new User('2220f936-f5c8-11eb-b4ed-2b3aa0cfa9c8', 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            new User('2523779e-f5c8-11eb-b3a4-5b2991fefafc', 'evan.spiegel', 'Evan', 'Spiegel'),
            new User('27d62af4-f5c8-11eb-bd85-27562478e247', 'jack.dorsey', 'Jack', 'Dorsey'),
        ];
    }

    public function findAll(): array
    {
        return array_values($this->users);
    }

    public function findUserOfId(ID $id): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id->asString()) {
                return $user;
            }
        }

        throw new UserNotFoundException();
    }
}
