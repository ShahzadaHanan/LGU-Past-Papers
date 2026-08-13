<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\PaperSubmission;

class PaperSubmissionRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM paper_submissions ORDER BY id DESC");
        return array_map(fn($row) => PaperSubmission::fromArray($row), $rows);
    }

    public function find(int $id): ?PaperSubmission
    {
        $row = $this->db->fetch("SELECT * FROM paper_submissions WHERE id = ?", [$id]);
        return $row ? PaperSubmission::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO paper_submissions (name, email, whatsapp, subject, description, image_path, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['email'], $data['whatsapp'], $data['subject'], $data['description'] ?? null, $data['image_path'], $data['status'] ?? 'pending']
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE paper_submissions SET name = ?, email = ?, whatsapp = ?, subject = ?, description = ?, image_path = ?, status = ? WHERE id = ?",
            [$data['name'], $data['email'], $data['whatsapp'], $data['subject'], $data['description'] ?? null, $data['image_path'], $data['status'] ?? 'pending', $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM paper_submissions WHERE id = ?", [$id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->db->execute("UPDATE paper_submissions SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function getByStatus(string $status): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM paper_submissions WHERE status = ? ORDER BY id DESC", [$status]);
        return array_map(fn($row) => PaperSubmission::fromArray($row), $rows);
    }
}
