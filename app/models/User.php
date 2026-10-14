<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    protected array $fillable = [
        'name',
        'email',
        'phone',
        'password_hash',
        'profile_image',
        'auth_provider',
        'google_id',
    ];

    public function findByEmail(string $email): array|false
    {
        return $this->database->fetch(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$email]
        );
    }

    public function findByGoogleId(string $googleId): array|false
    {
        return $this->database->fetch(
            "SELECT * FROM users WHERE google_id = ? LIMIT 1",
            [$googleId]
        );
    }

    public function emailExists(string $email): bool
    {
        return (bool) $this->database->fetch(
            "SELECT id FROM users WHERE email = ? LIMIT 1",
            [$email]
        );
    }

    public function updateLastLogin(int $id): bool
    {
        return $this->database->execute(
            "UPDATE users SET last_login_at = NOW() WHERE id = ?",
            [$id]
        );
    }
}
