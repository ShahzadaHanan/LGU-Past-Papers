<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected Database $database;

    protected string $table;

    protected string $primaryKey = 'id';

    protected array $fillable = [];

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function all(
        string $orderBy = 'id ASC'
    ): array {

        return $this->database->fetchAll(
            "SELECT * FROM {$this->table}
            ORDER BY {$orderBy}"
        );

    }

    public function find(
        int $id
    ): array|false {

        return $this->database->fetch(
            "SELECT *
            FROM {$this->table}
            WHERE {$this->primaryKey} = ?
            LIMIT 1",
            [$id]
        );

    }

    public function insert(
        array $data
    ): bool {

        $columns = array_keys($data);

        $placeholders = implode(
            ',',
            array_fill(
                0,
                count($columns),
                '?'
            )
        );

        return $this->database->execute(
            sprintf(
                "INSERT INTO %s (%s)
                 VALUES (%s)",
                $this->table,
                implode(',', $columns),
                $placeholders
            ),
            array_values($data)
        );

    }

    public function update(
        int $id,
        array $data
    ): bool {

        $set = [];

        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
        }

        $values = array_values($data);

        $values[] = $id;

        return $this->database->execute(
            sprintf(
                "UPDATE %s
                SET %s
                WHERE %s = ?",
                $this->table,
                implode(',', $set),
                $this->primaryKey
            ),
            $values
        );

    }

    public function delete(
        int $id
    ): bool {

        return $this->database->execute(
            "DELETE
            FROM {$this->table}
            WHERE {$this->primaryKey}=?",
            [$id]
        );

    }
}