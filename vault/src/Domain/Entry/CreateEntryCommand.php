<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\Command\Command;
use App\CQRS\ID\Identifier;

final class CreateEntryCommand implements Command
{
    public const TOPIC = 'entry.create';

    private Identifier $id;
    private string $title;
    private string $url;
    private string $username;
    private string $password;

    public function __construct(Identifier $id, string $title, string $url, string $username, string $password)
    {
        $this->id = $id;
        $this->title = $title;
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId(): Identifier
    {
        return $this->id;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
