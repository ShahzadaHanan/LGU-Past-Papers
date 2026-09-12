<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberRepository
{
    public function __construct(private Database $db) {}

    public function all(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM newsletter_subscribers ORDER BY id DESC");
        return array_map(fn($row) => NewsletterSubscriber::fromArray($row), $rows);
    }

    public function find(int $id): ?NewsletterSubscriber
    {
        $row = $this->db->fetch("SELECT * FROM newsletter_subscribers WHERE id = ?", [$id]);
        return $row ? NewsletterSubscriber::fromArray($row) : null;
    }

    public function create(array $data): bool
    {
        return $this->db->execute(
            "INSERT INTO newsletter_subscribers (email, is_verified, unsubscribe_token) VALUES (?, ?, ?)",
            [$data['email'], $data['is_verified'] ?? 1, $data['unsubscribe_token'] ?? bin2hex(random_bytes(16))]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->execute(
            "UPDATE newsletter_subscribers SET email = ?, is_verified = ? WHERE id = ?",
            [$data['email'], $data['is_verified'] ?? 1, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM newsletter_subscribers WHERE id = ?", [$id]);
    }

    public function updateStatus(int $id, bool $isVerified): bool
    {
        return $this->db->execute("UPDATE newsletter_subscribers SET is_verified = ? WHERE id = ?", [$isVerified ? 1 : 0, $id]);
    }

    public function deactivateByToken(string $token): bool
    {
        return $this->db->execute("UPDATE newsletter_subscribers SET is_verified = 0 WHERE unsubscribe_token = ?", [$token]);
    }
}
