<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function put(
        string $key,
        mixed $value
    ): void {

        $_SESSION[$key] = $value;

    }

    public function get(
        string $key,
        mixed $default = null
    ): mixed {

        return $_SESSION[$key] ?? $default;

    }

    public function has(
        string $key
    ): bool {

        return array_key_exists(
            $key,
            $_SESSION
        );

    }

    public function forget(
        string $key
    ): void {

        unset($_SESSION[$key]);

    }

    public function destroy(): void
    {
        $_SESSION = [];

        if (
            ini_get(
                'session.use_cookies'
            )
        ) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );

        }

        session_destroy();
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function flash(
        string $key,
        mixed $value = null
    ): mixed {

        if ($value !== null) {

            $_SESSION['_flash'][$key] = $value;

            return null;

        }

        $message =
            $_SESSION['_flash'][$key]
            ?? null;

        unset(
            $_SESSION['_flash'][$key]
        );

        return $message;

    }

    public function all(): array
    {
        return $_SESSION;
    }


    public function old(
    string $key,
    mixed $default=null
):mixed{

    return $_SESSION['_old'][$key]
    ?? $default;

}

public function setOld(
    array $input
):void{

    $_SESSION['_old']=$input;

}

public function clearOld():void{

    unset($_SESSION['_old']);

}

public function setErrors(
    array $errors
):void{

    $_SESSION['_errors']=$errors;

}

public function errors():array{

    $errors=$_SESSION['_errors']
    ??[];

    unset($_SESSION['_errors']);

    return $errors;

}
}