<?php

declare(strict_types=1);

namespace App\CQRS\Event;

interface Event
{
    public static function fromPayload(array $payload): self;

    public function getPayload(): array;

    public function getTopic(): string;

    public function getVersion(): int;
}
