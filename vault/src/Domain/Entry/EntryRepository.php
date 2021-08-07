<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\Event\Messages;
use App\CQRS\EventStream\AbstractEventStream;
use App\CQRS\EventStream\AbstractEventStreamRepository;

final class EntryRepository extends AbstractEventStreamRepository
{
    protected function createStreamWithMessages(Messages $messages): AbstractEventStream
    {
        return EntryEventStream::from($messages);
    }
}
