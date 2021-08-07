<?php

declare(strict_types=1);

namespace App\Domain\Entry;

use App\CQRS\ID\Identifier;
use Exception;
use mysqli;

final class EntryProjection
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function addEntry(Identifier $id, Identifier $parent, string $title, string $url, string $username, string $password): void
    {
        try {
            $this->connection->begin_transaction();

            $sql = <<<QUERY
                INSERT INTO `projection_entry` (
                    `entry_id`,
                    `parent_id`,
                    `title`, 
                    `url`, 
                    `username`, 
                    `password` 
                ) 
                VALUES (?, ?, ?, ?, ?, ?);
            QUERY;

            $statement = $this->connection->prepare($sql);
            $params = [
                $id->asString(),
                $parent->asString(),
                $title,
                $url,
                $username,
                $password,
            ];
            $statement->bind_param('ssssss', ...$params);
            $statement->execute();

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw new Exception('Could not save entry', 0, $exception);
        }
    }
}
