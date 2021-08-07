<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\Event\Event;
use App\CQRS\ID\Identifier;
use App\CQRS\ID\UUID;

final class EntryCreatedEvent implements Event
{
    public const TOPIC = 'entry.created';
    public const VERSION = 1;

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

    public static function fromPayload(array $payload): Event
    {
        return new self(
            UUID::fromV4($payload['id']),
            $payload['title'],
            $payload['url'],
            $payload['username'],
            $payload['password'],
        );
    }

    public function getPayload(): array
    {
        return [
            'id' => $this->id->asString(),
            'title' => $this->title,
            'url' => $this->url,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getVersion(): int
    {
        return self::VERSION;
    }

    public function getId(): Identifier
    {
        return $this->id;
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
