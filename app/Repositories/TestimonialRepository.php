<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Testimonial;

class TestimonialRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM testimonials ORDER BY display_order ASC, id DESC");
        return array_map(fn($row) => Testimonial::fromArray($row), $rows);
    }

    public function active(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY display_order ASC, id DESC");
        return array_map(fn($row) => Testimonial::fromArray($row), $rows);
    }

    public function find(int $id): ?Testimonial
    {
        $row = $this->db->fetch("SELECT * FROM testimonials WHERE id = ?", [$id]);
        return $row ? Testimonial::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO testimonials (name, degree, photo, quote, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['degree'] ?? null, $data['photo'] ?? null, $data['quote'], $data['display_order'] ?? 0, $data['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE testimonials SET name = ?, degree = ?, photo = ?, quote = ?, display_order = ?, is_active = ? WHERE id = ?",
            [$data['name'], $data['degree'] ?? null, $data['photo'] ?? null, $data['quote'], $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM testimonials WHERE id = ?", [$id]);
    }
}
