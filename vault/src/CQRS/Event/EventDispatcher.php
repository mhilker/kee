<?php

declare(strict_types=1);

namespace App\CQRS\Event;

interface EventDispatcher
{
    public function dispatch(): void;
}
