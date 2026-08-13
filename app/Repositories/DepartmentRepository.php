<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;

class DepartmentRepository extends Repository
{
    protected string $table='departments';

    public function search(
        string $search='',
        int $limit=15,
        int $offset=0
    ):array{

        return $this->database->fetchAll(
            "SELECT *
            FROM departments
            WHERE name LIKE ?
            ORDER BY display_order ASC,id DESC
            LIMIT {$limit}
            OFFSET {$offset}",
            [
                "%{$search}%"
            ]
        );

    }

    public function count(
        string $search=''
    ):int{

        $row=$this->database->fetch(
            "SELECT COUNT(*) total
            FROM departments
            WHERE name LIKE ?",
            [
                "%{$search}%"
            ]
        );

        return (int)$row['total'];

    }
}