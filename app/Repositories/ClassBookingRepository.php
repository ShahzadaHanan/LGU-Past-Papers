<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\ClassBooking;

class ClassBookingRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM class_bookings ORDER BY id DESC");
        return array_map(fn($row) => ClassBooking::fromArray($row), $rows);
    }

    public function find(int $id): ?ClassBooking
    {
        $row = $this->db->fetch("SELECT * FROM class_bookings WHERE id = ?", [$id]);
        return $row ? ClassBooking::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO class_bookings (name, email, whatsapp, subject, topic, fee_type, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['email'], $data['whatsapp'], $data['subject'], $data['topic'] ?? null, $data['fee_type'], $data['description'] ?? null, $data['status'] ?? 'new']
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE class_bookings SET name = ?, email = ?, whatsapp = ?, subject = ?, topic = ?, fee_type = ?, description = ?, status = ? WHERE id = ?",
            [$data['name'], $data['email'], $data['whatsapp'], $data['subject'], $data['topic'] ?? null, $data['fee_type'], $data['description'] ?? null, $data['status'] ?? 'new', $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM class_bookings WHERE id = ?", [$id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->db->execute("UPDATE class_bookings SET status = ? WHERE id = ?", [$status, $id]);
    }
}
