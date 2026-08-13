<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\VideoCategory;

class VideoCategoryRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM video_categories ORDER BY display_order ASC");
        return array_map(fn($row) => VideoCategory::fromArray($row), $rows);
    }

    public function find(int $id): ?VideoCategory
    {
        $row = $this->db->fetch("SELECT * FROM video_categories WHERE id = ?", [$id]);
        return $row ? VideoCategory::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO video_categories (name, slug, display_order, is_active) VALUES (?, ?, ?, ?)",
            [$data['name'], $data['slug'], $data['display_order'] ?? 0, $data['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE video_categories SET name = ?, slug = ?, display_order = ?, is_active = ? WHERE id = ?",
            [$data['name'], $data['slug'], $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM video_categories WHERE id = ?", [$id]);
    }
}
