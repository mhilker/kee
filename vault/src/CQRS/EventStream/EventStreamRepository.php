<?php

declare(strict_types=1);

namespace App\CQRS\EventStream;

use App\CQRS\EventStream\Exception\EventStreamNotFoundException;
use App\CQRS\EventStream\Exception\EventStreamNotSavedException;
use App\CQRS\ID\Identifier;

interface EventStreamRepository
{
    /**
     * @throws EventStreamNotSavedException
     */
    public function save(AbstractEventStream $stream): void;

    /**
     * @throws EventStreamNotFoundException
     */
    public function load(Identifier $id): AbstractEventStream;
}
