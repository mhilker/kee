<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ID
{
    private UuidInterface $value;

    private function __construct(string $id)
    {
        $this->value = Uuid::fromString($id);
    }

    public static function fromString(mixed $id): self
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->value->toString();
    }
}
