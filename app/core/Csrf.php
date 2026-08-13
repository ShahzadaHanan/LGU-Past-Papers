<?php

declare(strict_types=1);

namespace App\Core;

class Csrf
{
    public function token(): string
    {
        if (
            empty($_SESSION['csrf_token'])
        ) {
            $_SESSION['csrf_token'] =
                bin2hex(
                    random_bytes(32)
                );
        }

        return $_SESSION['csrf_token'];
    }

    public function validate(
        ?string $token
    ): bool {

        if (
            empty($_SESSION['csrf_token'])
        ) {
            return false;
        }

        return hash_equals(
            $_SESSION['csrf_token'],
            $token ?? ''
        );

    }
}
