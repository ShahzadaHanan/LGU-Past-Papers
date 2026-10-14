<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Subject;

class SubjectRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM subjects ORDER BY sub_department_id ASC, display_order ASC, name ASC");
        $results = $stmt->fetchAll();
        return array_map(fn($row) => Subject::fromArray($row), $results);
    }

    public function allBySubDepartment(int $subDepartmentId): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM subjects WHERE sub_department_id = :sub_department_id ORDER BY display_order ASC, name ASC",
            ['sub_department_id' => $subDepartmentId]
        );
        $results = $stmt->fetchAll();
        return array_map(fn($row) => Subject::fromArray($row), $results);
    }

    public function paginate(int $subDepartmentId, string $search, int $limit, int $offset): array
    {
        [$where, $params] = $this->filterClause($subDepartmentId, $search);

        $stmt = $this->db->query(
            "SELECT * FROM subjects{$where} ORDER BY sub_department_id ASC, display_order ASC, name ASC LIMIT {$limit} OFFSET {$offset}",
            $params
        );
        $results = $stmt->fetchAll();
        return array_map(fn($row) => Subject::fromArray($row), $results);
    }

    public function count(int $subDepartmentId, string $search): int
    {
        [$where, $params] = $this->filterClause($subDepartmentId, $search);

        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM subjects{$where}", $params);
        return (int) $stmt->fetch()['total'];
    }

    private function filterClause(int $subDepartmentId, string $search): array
    {
        $conditions = [];
        $params = [];

        if ($subDepartmentId > 0) {
            $conditions[] = 'sub_department_id = :sub_department_id';
            $params['sub_department_id'] = $subDepartmentId;
        }

        if ($search !== '') {
            $conditions[] = 'name LIKE :search';
            $params['search'] = '%' . $search . '%';
        }

        $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';

        return [$where, $params];
    }

    public function find(int $id): ?Subject
    {
        $stmt = $this->db->query("SELECT * FROM subjects WHERE id = :id", ['id' => $id]);
        $row = $stmt->fetch();
        return $row ? Subject::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        $this->db->query(
            "INSERT INTO subjects (sub_department_id, name, code, semester, display_order, is_active)
            VALUES (:sub_department_id, :name, :code, :semester, :display_order, :is_active)",
            [
                'sub_department_id' => $data['sub_department_id'],
                'name' => $data['name'],
                'code' => $data['code'] ?? null,
                'semester' => $data['semester'] ?? null,
                'display_order' => $data['display_order'] ?? 0,
                'is_active' => $data['is_active'] ?? 1,
            ]
        );

        return true;
    }

    public function update(int $id, array $data): bool
    {
        $this->db->query(
            "UPDATE subjects SET sub_department_id = :sub_department_id, name = :name, code = :code, semester = :semester, display_order = :display_order, is_active = :is_active WHERE id = :id",
            [
                'id' => $id,
                'sub_department_id' => $data['sub_department_id'],
                'name' => $data['name'],
                'code' => $data['code'] ?? null,
                'semester' => $data['semester'] ?? null,
                'display_order' => $data['display_order'] ?? 0,
                'is_active' => $data['is_active'] ?? 1,
            ]
        );

        return true;
    }

    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM subjects WHERE id = :id", ['id' => $id])->rowCount() > 0;
    }
}
