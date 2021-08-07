<?php

declare(strict_types=1);

namespace App\CQRS\Command;

interface CommandPublisher
{
    public function publish(Command $command): void;

    public function isEmpty(): bool;

    public function dequeue(): Command;
}
