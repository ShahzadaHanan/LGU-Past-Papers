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
        // Database::query() runs with PDO::ERRMODE_EXCEPTION, so reaching
        // the return means the insert already succeeded — no need to
        // (and no safe way to) re-execute or check rowCount() here.
        $this->db->query(
            "INSERT INTO sub_departments (department_id, name, slug, description, display_order, is_active)
            VALUES (:department_id, :name, :slug, :description, :display_order, :is_active)",
            [
                'department_id' => $data['department_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'display_order' => $data['display_order'] ?? 0,
                'is_active' => $data['is_active'] ?? 1,
            ]
        );

        return true;
    }

    public function update(int $id, array $data): bool
    {
        $this->db->query(
            "UPDATE sub_departments SET department_id = :department_id, name = :name, slug = :slug, description = :description WHERE id = :id",
            [
                'id' => $id,
                'department_id' => $data['department_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null
            ]
        );

        return true;
    }

    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM sub_departments WHERE id = :id", ['id' => $id])->rowCount() > 0;
    }
}
