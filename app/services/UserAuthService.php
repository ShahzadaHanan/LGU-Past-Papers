<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

/**
 * Auth for public site visitors (registration + login), kept entirely
 * separate from AuthService (admin panel staff) — different session key
 * ('site_user' vs 'auth') and a different table (`users` vs `admins`), so
 * a visitor account can never be confused with, or escalate into, admin
 * access.
 */
class UserAuthService
{
    public function __construct(
        private User $users,
        private Validator $validator,
        private Session $session
    ) {
    }

    public function register(
        string $name,
        string $email,
        string $phone,
        string $password,
        string $passwordConfirmation,
        ?string $profileImage = null
    ): bool {
        $this->validator
            ->required('name', $name)
            ->required('email', $email)
            ->email('email', $email)
            ->required('phone', $phone)
            ->required('password', $password)
            ->min('password', $password, 8);

        if ($this->validator->fails()) {
            throw new \Exception('Please enter a valid name, email, phone number and a password of at least 8 characters.');
        }

        if ($password !== $passwordConfirmation) {
            throw new \Exception('Password and confirm password do not match.');
        }

        if ($this->users->emailExists($email)) {
            throw new \Exception('An account with this email already exists. Try logging in instead.');
        }

        $this->users->insert([
            'name' => trim($name),
            'email' => trim($email),
            'phone' => trim($phone),
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'profile_image' => $profileImage,
            'auth_provider' => 'local',
        ]);

        return $this->login($email, $password);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);

        if (!$user || empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        return $this->completeLogin($user);
    }

    /**
     * Verifies a Google Identity Services ID token (via Google's own
     * tokeninfo endpoint — no OAuth SDK dependency needed for this one
     * check) and finds-or-creates the matching local account, then logs
     * it in. Requires GOOGLE_CLIENT_ID to be configured; the header only
     * renders the Google button when that's set, so this is only reached
     * when it is.
     */
    public function loginWithGoogleToken(string $idToken): bool
    {
        $clientId = env('GOOGLE_CLIENT_ID', '');

        if ($clientId === '') {
            throw new \Exception('Google sign-in is not configured.');
        }

        $response = @file_get_contents(
            'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken)
        );

        $payload = $response ? json_decode($response, true) : null;

        if (!is_array($payload) || empty($payload['email']) || ($payload['aud'] ?? '') !== $clientId) {
            throw new \Exception('Could not verify Google sign-in. Please try again.');
        }

        $googleId = (string) ($payload['sub'] ?? '');
        $email = (string) $payload['email'];
        $name = (string) ($payload['name'] ?? $email);
        $picture = $payload['picture'] ?? null;

        $user = $this->users->findByGoogleId($googleId);

        if (!$user) {
            $user = $this->users->findByEmail($email);
        }

        if (!$user) {
            $this->users->insert([
                'name' => $name,
                'email' => $email,
                'phone' => '',
                'password_hash' => null,
                'profile_image' => $picture,
                'auth_provider' => 'google',
                'google_id' => $googleId,
            ]);

            $user = $this->users->findByEmail($email);
        }

        return $this->completeLogin($user);
    }

    private function completeLogin(array $user): bool
    {
        $this->session->regenerate();

        unset($user['password_hash']);

        $this->session->put('site_user', $user);

        $this->users->updateLastLogin((int) $user['id']);

        return true;
    }

    public function logout(): void
    {
        $this->session->forget('site_user');
    }

    public function check(): bool
    {
        return $this->session->has('site_user');
    }

    public function user(): ?array
    {
        return $this->session->get('site_user');
    }
}
