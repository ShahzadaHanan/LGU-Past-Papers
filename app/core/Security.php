<?php

declare(strict_types=1);

namespace App\Core;

class Security
{
    public function csrfToken(): string
    {
        if (!isset($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }

    public function verifyCsrf(string $token): bool
    {
        return hash_equals(
            $_SESSION['_csrf'] ?? '',
            $token
        );
    }

    public function hashPassword(string $password): string
    {
        return password_hash(
            $password,
            PASSWORD_DEFAULT
        );
    }

    public function verifyPassword(
        string $password,
        string $hash
    ): bool
    {
        return password_verify(
            $password,
            $hash
        );
    }

    public function escape(string $value): string
    {
        return htmlspecialchars(
            $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }

    public function randomToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}