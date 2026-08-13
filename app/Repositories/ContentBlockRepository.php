<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\ContentBlock;

class ContentBlockRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM content_blocks ORDER BY page_key ASC, display_order ASC");
        return array_map(fn($row) => ContentBlock::fromArray($row), $rows);
    }

    public function find(int $id): ?ContentBlock
    {
        $row = $this->db->fetch("SELECT * FROM content_blocks WHERE id = ?", [$id]);
        return $row ? ContentBlock::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO content_blocks (page_key, heading, body, display_order, is_active) VALUES (?, ?, ?, ?, ?)",
            [$data['page_key'], $data['heading'], $data['body'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE content_blocks SET page_key = ?, heading = ?, body = ?, display_order = ?, is_active = ? WHERE id = ?",
            [$data['page_key'], $data['heading'], $data['body'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM content_blocks WHERE id = ?", [$id]);
    }
}
