<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Admin extends Model
{
    protected string $table = 'admins';

    protected array $fillable = [
        'name',
        'email',
        'password_hash',
        'role',
        'last_login'
    ];

    public function findByEmail(
        string $email
    ): array|false {

        return $this->database->fetch(
            "SELECT *
            FROM admins
            WHERE email = ?
            LIMIT 1",
            [$email]
        );

    }

    public function updateLastLogin(
        int $id
    ): bool {

        return $this->database->execute(
            "UPDATE admins
            SET last_login = NOW(),
                failed_attempts = 0,
                locked_until = NULL
            WHERE id = ?",
            [$id]
        );

    }

    public function incrementFailedAttempts(
        int $id
    ): int {

        $this->database->execute(
            "UPDATE admins
            SET failed_attempts = failed_attempts + 1
            WHERE id = ?",
            [$id]
        );

        $admin = $this->database->fetch(
            "SELECT failed_attempts FROM admins WHERE id = ?",
            [$id]
        );

        $attempts = (int) ($admin['failed_attempts'] ?? 0);

        // Lock the account for 15 minutes after 3 failed attempts in a row.
        if ($attempts >= 3) {
            $this->database->execute(
                "UPDATE admins
                SET locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                WHERE id = ?",
                [$id]
            );
        }

        return $attempts;
    }

    public function isLocked(array $admin): bool
    {
        if (empty($admin['locked_until'])) {
            return false;
        }

        return strtotime((string) $admin['locked_until']) > time();
    }

    public function emailExists(
        string $email
    ): bool {

        return (bool) $this->database->fetch(
            "SELECT id
            FROM admins
            WHERE email = ?
            LIMIT 1",
            [$email]
        );

    }
}