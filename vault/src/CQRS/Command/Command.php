<?php

declare(strict_types=1);

namespace App\CQRS\Command;

use App\CQRS\ID\Identifier;

interface Command
{
    public function getId(): Identifier;

    public function getTopic(): string;
}
