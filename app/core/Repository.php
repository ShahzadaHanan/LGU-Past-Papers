<?php

declare(strict_types=1);

namespace App\Core;

abstract class Repository
{
    protected string $table;

    public function __construct(
        protected Database $database
    ) {
    }

    public function all(
        string $orderBy='id',
        string $direction='DESC'
    ): array {

        return $this->database->fetchAll(
            "SELECT * FROM {$this->table}
            ORDER BY {$orderBy} {$direction}"
        );

    }

    public function find(
        int $id
    ): array|false {

        return $this->database->fetch(
            "SELECT *
            FROM {$this->table}
            WHERE id=?",
            [$id]
        );

    }

    /**
     * Column names can never be bound as PDO parameters, so every caller
     * must whitelist its own fields in the Service layer before reaching
     * here (see DepartmentService::create() for the pattern). This is the
     * second layer of defense: it refuses anything that isn't a plausible
     * column identifier, so a stray attacker-controlled array key can never
     * reach raw SQL even if a Service forgets to whitelist.
     */
    private function assertValidColumn(string $column): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new \InvalidArgumentException(
                "Refusing to build a query with invalid column name: {$column}"
            );
        }
    }

    public function create(
        array $data
    ): bool {

        $columns=array_keys($data);

        foreach ($columns as $column) {
            $this->assertValidColumn($column);
        }

        $placeholders=array_fill(
            0,
            count($columns),
            '?'
        );

        return $this->database->execute(
            sprintf(
                "INSERT INTO %s (%s)
                VALUES (%s)",
                $this->table,
                implode(',',$columns),
                implode(',',$placeholders)
            ),
            array_values($data)
        );

    }

    public function update(
        int $id,
        array $data
    ): bool {

        $fields=[];

        foreach($data as $column=>$value){

            $this->assertValidColumn($column);

            $fields[]="{$column}=?";

        }

        $values=array_values($data);

        $values[]=$id;

        return $this->database->execute(
            sprintf(
                "UPDATE %s
                SET %s
                WHERE id=?",
                $this->table,
                implode(',',$fields)
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
            WHERE id=?",
            [$id]
        );

    }
}