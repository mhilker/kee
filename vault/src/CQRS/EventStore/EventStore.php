<?php

declare(strict_types=1);

namespace App\CQRS\EventStore;

use App\CQRS\Event\Messages;
use App\CQRS\EventStore\Exception\EventStoreException;
use App\CQRS\ID\Identifier;

interface EventStore
{
    /**
     * @throws EventStoreException
     */
    public function store(Messages $messages): void;

    /**
     * @throws EventStoreException
     */
    public function load(Identifier $id): Messages;
}
