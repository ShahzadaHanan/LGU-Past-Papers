<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Video;

class VideoRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll(
            "SELECT v.*, vc.name AS category_name FROM videos v
             LEFT JOIN video_categories vc ON vc.id = v.category_id
             ORDER BY v.display_order ASC, v.id DESC"
        );
        return array_map(fn($row) => Video::fromArray($row), $rows);
    }

    public function find(int $id): ?Video
    {
        $row = $this->db->fetch("SELECT * FROM videos WHERE id = ?", [$id]);
        return $row ? Video::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO videos (category_id, youtube_url, youtube_video_id, title, description, display_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['category_id'], $data['youtube_url'], $data['youtube_video_id'] ?? null,
                $data['title'], $data['description'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1
            ]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE videos SET category_id = ?, youtube_url = ?, youtube_video_id = ?, title = ?, description = ?, display_order = ?, is_active = ?
             WHERE id = ?",
            [
                $data['category_id'], $data['youtube_url'], $data['youtube_video_id'] ?? null,
                $data['title'], $data['description'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id
            ]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM videos WHERE id = ?", [$id]);
    }
}
