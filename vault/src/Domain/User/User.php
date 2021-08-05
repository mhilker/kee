<?php
declare(strict_types=1);

namespace App\Domain\User;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class User implements JsonSerializable
{
    private ?string $id;

    private string $username;

    private string $firstName;

    private string $lastName;

    public function __construct(?string $id, string $username, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->username = strtolower($username);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    #[ArrayShape([
        'id' => "string|null",
        'username' => "string",
        'firstName' => "string",
        'lastName' => "string",
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
