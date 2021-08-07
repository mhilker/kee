<?php

declare(strict_types=1);

namespace App\CQRS\EventStore;

use App\CQRS\Event\Message;
use App\CQRS\EventStore\Exception\EventMapException;

interface EventMap
{
    /**
     * @throws EventMapException
     */
    public function reconstitute(array $data): Message;
}
