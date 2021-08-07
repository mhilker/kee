<?php

declare(strict_types=1);

namespace App\CQRS\ID;

interface Identifier
{
    public function asString(): string;
}
