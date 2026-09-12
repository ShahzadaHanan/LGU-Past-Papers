<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Paper;

class PaperRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll(
            "SELECT p.*, sd.name AS sub_department_name
             FROM papers p
             LEFT JOIN sub_departments sd ON sd.id = p.sub_department_id
             ORDER BY p.id DESC"
        );

        return array_map(fn($row) => Paper::fromArray($row), $rows);
    }

    public function find(int $id): ?Paper
    {
        $row = $this->db->fetch("SELECT * FROM papers WHERE id = ?", [$id]);
        return $row ? Paper::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO papers
                (sub_department_id, exam_type, session, subject_name, slug, paper_image, solution_image, download_file, meta_title, meta_description)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['sub_department_id'],
                $data['exam_type'],
                $data['session'],
                $data['subject_name'],
                $data['slug'],
                $data['paper_image'],
                $data['solution_image'] ?? null,
                $data['download_file'] ?? null,
                $data['meta_title'] ?? null,
                $data['meta_description'] ?? null,
            ]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE papers SET
                sub_department_id = ?, exam_type = ?, session = ?, subject_name = ?, slug = ?,
                paper_image = ?, solution_image = ?, download_file = ?, meta_title = ?, meta_description = ?
             WHERE id = ?",
            [
                $data['sub_department_id'],
                $data['exam_type'],
                $data['session'],
                $data['subject_name'],
                $data['slug'],
                $data['paper_image'],
                $data['solution_image'] ?? null,
                $data['download_file'] ?? null,
                $data['meta_title'] ?? null,
                $data['meta_description'] ?? null,
                $id,
            ]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM papers WHERE id = ?", [$id]);
    }

    public function search(string $search, int $limit, int $offset): array
    {
        // LIMIT/OFFSET bound as PDO params come through as quoted strings
        // (MySQL rejects that syntax), so they're interpolated directly —
        // safe here since both are already typed `int` by the signature.
        if ($search !== '') {
            $rows = $this->db->fetchAll(
                "SELECT p.*, sd.name AS sub_department_name
                 FROM papers p
                 LEFT JOIN sub_departments sd ON sd.id = p.sub_department_id
                 WHERE p.subject_name LIKE ? OR p.session LIKE ?
                 ORDER BY p.id DESC LIMIT {$limit} OFFSET {$offset}",
                ["%{$search}%", "%{$search}%"]
            );
        } else {
            $rows = $this->db->fetchAll(
                "SELECT p.*, sd.name AS sub_department_name
                 FROM papers p
                 LEFT JOIN sub_departments sd ON sd.id = p.sub_department_id
                 ORDER BY p.id DESC LIMIT {$limit} OFFSET {$offset}",
                []
            );
        }

        return array_map(fn($row) => Paper::fromArray($row), $rows);
    }

    public function count(string $search = ''): int
    {
        if ($search !== '') {
            $row = $this->db->fetch(
                "SELECT COUNT(*) AS total FROM papers WHERE subject_name LIKE ? OR session LIKE ?",
                ["%{$search}%", "%{$search}%"]
            );
        } else {
            $row = $this->db->fetch("SELECT COUNT(*) AS total FROM papers");
        }

        return (int) ($row['total'] ?? 0);
    }
}
