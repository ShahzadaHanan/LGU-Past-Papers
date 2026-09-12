<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Video;

class VideoRepository
{
    public function __construct(private Database $db) {}

    private const BASE_SELECT = "
        SELECT v.*, vc.name AS category_name, sd.name AS sub_department_name,
               d.id AS department_id, d.name AS department_name
        FROM videos v
        LEFT JOIN video_categories vc ON vc.id = v.category_id
        LEFT JOIN sub_departments sd ON sd.id = v.sub_department_id
        LEFT JOIN departments d ON d.id = sd.department_id
    ";

    public function all(): array
    {
        $rows = $this->db->fetchAll(self::BASE_SELECT . " ORDER BY v.display_order ASC, v.id DESC");
        return array_map(fn($row) => Video::fromArray($row), $rows);
    }

    public function find(int $id): ?Video
    {
        $row = $this->db->fetch(self::BASE_SELECT . " WHERE v.id = ?", [$id]);
        return $row ? Video::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO videos (category_id, sub_department_id, subject_name, youtube_url, youtube_video_id, title, description, display_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['category_id'], $data['sub_department_id'] ?: null, $data['subject_name'] ?: null,
                $data['youtube_url'], $data['youtube_video_id'] ?? null,
                $data['title'], $data['description'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1
            ]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE videos SET category_id = ?, sub_department_id = ?, subject_name = ?, youtube_url = ?, youtube_video_id = ?, title = ?, description = ?, display_order = ?, is_active = ?
             WHERE id = ?",
            [
                $data['category_id'], $data['sub_department_id'] ?: null, $data['subject_name'] ?: null,
                $data['youtube_url'], $data['youtube_video_id'] ?? null,
                $data['title'], $data['description'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id
            ]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM videos WHERE id = ?", [$id]);
    }

    /**
     * Public /lectures listing, filtered by the Department -> Degree ->
     * Subject cascade (each level optional; a narrower filter implies the
     * wider ones already matched).
     */
    public function filtered(?string $departmentSlug, ?string $subDepartmentSlug, ?string $subject, int $limit, int $offset): array
    {
        [$where, $params] = $this->buildFilter($departmentSlug, $subDepartmentSlug, $subject);

        $rows = $this->db->fetchAll(
            self::BASE_SELECT . " WHERE v.is_active = 1 {$where}
             ORDER BY v.display_order ASC, v.id DESC LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        return array_map(fn($row) => Video::fromArray($row), $rows);
    }

    public function countFiltered(?string $departmentSlug, ?string $subDepartmentSlug, ?string $subject): int
    {
        [$where, $params] = $this->buildFilter($departmentSlug, $subDepartmentSlug, $subject);

        $row = $this->db->fetch(
            "SELECT COUNT(*) AS total
             FROM videos v
             LEFT JOIN sub_departments sd ON sd.id = v.sub_department_id
             WHERE v.is_active = 1 {$where}",
            $params
        );

        return (int) ($row['total'] ?? 0);
    }

    private function buildFilter(?string $departmentSlug, ?string $subDepartmentSlug, ?string $subject): array
    {
        $where = '';
        $params = [];

        if (!empty($subDepartmentSlug)) {
            $where .= " AND sd.slug = ?";
            $params[] = $subDepartmentSlug;
        } elseif (!empty($departmentSlug)) {
            $where .= " AND d.slug = ?";
            $params[] = $departmentSlug;
        }

        if (!empty($subject)) {
            $where .= " AND v.subject_name = ?";
            $params[] = $subject;
        }

        return [$where, $params];
    }

    /**
     * Distinct subject names per sub-department, among active videos only —
     * embedded client-side so the third filter dropdown can populate itself
     * instantly once a degree is picked, with no extra request.
     *
     * @return array<int, string[]>
     */
    public function subjectsBySubDepartment(): array
    {
        $rows = $this->db->fetchAll(
            "SELECT DISTINCT sub_department_id, subject_name FROM videos
             WHERE is_active = 1 AND sub_department_id IS NOT NULL AND subject_name IS NOT NULL AND subject_name != ''
             ORDER BY subject_name ASC"
        );

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['sub_department_id']][] = $row['subject_name'];
        }

        return $map;
    }
}
