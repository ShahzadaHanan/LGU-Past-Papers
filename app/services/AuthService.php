<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Models\Admin;

class AuthService
{
    public function __construct(
        private Admin $admins,
        private Session $session
    ) {}

    /**
     * How long an admin session may sit idle before it's forced back to the
     * login screen (the "session limit" requested for the admin panel).
     */
    private const IDLE_TIMEOUT_SECONDS = 1800; // 30 minutes

    public function login(
        string $email,
        string $password
    ): bool {

        $admin = $this->admins
            ->findByEmail($email);

        if (!$admin) {
            return false;
        }

        if ($this->admins->isLocked($admin)) {
            // Account is temporarily locked after 3 failed attempts.
            // TODO: once an SMTP/mail service is wired into this project,
            // send the admin an unlock/verification email here instead of
            // just waiting out the 15-minute cooldown.
            return false;
        }

        if (
            !password_verify(
                $password,
                $admin['password_hash']
            )
        )
         {
            $this->admins->incrementFailedAttempts((int) $admin['id']);
            return false;
        }

       

        $this->session->regenerate();

        unset(
            $admin['password_hash']
        );

        $this->session->put(
            'auth',
            $admin
        );

        $this->session->put(
            'last_activity',
            time()
        );

        $this->admins->updateLastLogin(
            (int) $admin['id']
        );

        return true;
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    /**
     * Returns false (and destroys the session) if the admin has been idle
     * longer than the allowed session limit.
     */
    public function checkIdleTimeout(): bool
    {
        if (!$this->check()) {
            return true;
        }

        $lastActivity = (int) $this->session->get('last_activity', time());

        if ((time() - $lastActivity) > self::IDLE_TIMEOUT_SECONDS) {
            $this->session->destroy();
            return false;
        }

        $this->session->put('last_activity', time());

        return true;
    }

    public function check(): bool
    {
        return $this->session->has('auth');
    }

    public function user(): ?array
    {
        return $this->session->get('auth');
    }

    public function id(): ?int
    {
        $user = $this->user();

        return $user
            ? (int) $user['id']
            : null;
    }

    public function role(): ?string
    {
        $user = $this->user();

        return $user['role'] ?? null;
    }

    public function hasRole(
        string $role
    ): bool {

        return $this->role() === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(
            'super_admin'
        );
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
}
