<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Announcement;

class AnnouncementRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM announcements ORDER BY display_order ASC, id ASC");
        return array_map(fn($row) => Announcement::fromArray($row), $rows);
    }

    public function active(): array
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM announcements WHERE is_active = 1 ORDER BY display_order ASC, id ASC"
        );
        return array_map(fn($row) => Announcement::fromArray($row), $rows);
    }

    public function find(int $id): ?Announcement
    {
        $row = $this->db->fetch("SELECT * FROM announcements WHERE id = ?", [$id]);
        return $row ? Announcement::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO announcements (message, link_url, link_label, display_order, is_active) VALUES (?, ?, ?, ?, ?)",
            [$data['message'], $data['link_url'] ?? null, $data['link_label'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE announcements SET message = ?, link_url = ?, link_label = ?, display_order = ?, is_active = ? WHERE id = ?",
            [$data['message'], $data['link_url'] ?? null, $data['link_label'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM announcements WHERE id = ?", [$id]);
    }
}
