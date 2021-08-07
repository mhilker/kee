<?php

declare(strict_types=1);

namespace App\CQRS\Event;

interface EventPublisher
{
    public function publish(Messages $messages): void;
}
