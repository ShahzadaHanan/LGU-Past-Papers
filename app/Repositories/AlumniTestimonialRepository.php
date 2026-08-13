<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\AlumniTestimonial;

class AlumniTestimonialRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM alumni ORDER BY display_order ASC, id DESC");
        return array_map(fn($row) => AlumniTestimonial::fromArray($row), $rows);
    }

    public function find(int $id): ?AlumniTestimonial
    {
        $row = $this->db->fetch("SELECT * FROM alumni WHERE id = ?", [$id]);
        return $row ? AlumniTestimonial::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO alumni (name, batch_year, degree, photo, testimonial, linkedin_url, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['batch_year'], $data['degree'], $data['photo'] ?? null, $data['testimonial'], $data['linkedin_url'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE alumni SET name = ?, batch_year = ?, degree = ?, photo = ?, testimonial = ?, linkedin_url = ?, display_order = ?, is_active = ? WHERE id = ?",
            [$data['name'], $data['batch_year'], $data['degree'], $data['photo'] ?? null, $data['testimonial'], $data['linkedin_url'] ?? null, $data['display_order'] ?? 0, $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM alumni WHERE id = ?", [$id]);
    }
}
