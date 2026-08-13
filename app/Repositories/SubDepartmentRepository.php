<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\SubDepartment;

class SubDepartmentRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM sub_departments");
        $results = $stmt->fetchAll();
        return array_map(fn($row) => SubDepartment::fromArray($row), $results);
    }

    public function find(int $id): ?SubDepartment
    {
        $stmt = $this->db->query("SELECT * FROM sub_departments WHERE id = :id", ['id' => $id]);
        $row = $stmt->fetch();
        return $row ? SubDepartment::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->query(
            "INSERT INTO sub_departments (department_id, name, description) VALUES (:department_id, :name, :description)",
            [
                'department_id' => $data['department_id'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null
            ]
        )->rowCount() > 0;
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->query(
            "UPDATE sub_departments SET department_id = :department_id, name = :name, description = :description WHERE id = :id",
            [
                'id' => $id,
                'department_id' => $data['department_id'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null
            ]
        )->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM sub_departments WHERE id = :id", ['id' => $id])->rowCount() > 0;
    }
}
