<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\HeroSlide;

class HeroSlideRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM hero_slides ORDER BY display_order ASC");
        return array_map(fn($row) => HeroSlide::fromArray($row), $rows);
    }

    public function find(int $id): ?HeroSlide
    {
        $row = $this->db->fetch("SELECT * FROM hero_slides WHERE id = ?", [$id]);
        return $row ? HeroSlide::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO hero_slides (image_path, caption, link_url, display_order, is_active) VALUES (?, ?, ?, ?, ?)",
            [$data['image_path'], $data['caption'] ?? null, $data['link_url'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE hero_slides SET image_path = ?, caption = ?, link_url = ?, display_order = ?, is_active = ? WHERE id = ?",
            [$data['image_path'], $data['caption'] ?? null, $data['link_url'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM hero_slides WHERE id = ?", [$id]);
    }
}
