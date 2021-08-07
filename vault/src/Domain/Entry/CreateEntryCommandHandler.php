<?php

declare(strict_types=1);

namespace App\Domain\Entry;

final class CreateEntryCommandHandler
{
    private EntryRepository $repository;

    public function __construct(EntryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateEntryCommand $command): void
    {
        $entry = EntryEventStream::create($command->getId(), $command->getTitle(), $command->getUrl(), $command->getPassword(), $command->getUsername());
        $this->repository->save($entry);
    }
}
