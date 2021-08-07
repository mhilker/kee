<?php

declare(strict_types=1);

namespace App\CQRS\Event;

interface EventHandler
{
    public function handle(Message $message): void;
}
