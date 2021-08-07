<?php

declare(strict_types=1);

namespace App\CQRS\Command;

interface CommandBus
{
    public function execute(Command $command): void;
}
