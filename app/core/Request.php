<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public function all(): array
    {
        return $_POST;
    }

    public function input(
        string $key,
        mixed $default = null
    ): mixed {

        // Falls back to $_GET so query-string params (search, page, filters
        // on GET admin listing routes) are read as well as POST form fields.
        return $_POST[$key] ?? $_GET[$key] ?? $default;

    }

    public function query(
        string $key,
        mixed $default = null
    ): mixed {

        return $_GET[$key] ?? $default;

    }

    public function file(
        string $key
    ): ?array {

        if (
            !isset($_FILES[$key]) ||
            $_FILES[$key]['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        return $_FILES[$key];

    }

    public function hasFile(
        string $key
    ): bool {

        return $this->file($key) !== null;

    }

    public function method(): string
    {
        return strtoupper(
            $_SERVER['REQUEST_METHOD']
        );
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function uri(): string
    {
        return strtok(
            $_SERVER['REQUEST_URI'],
            '?'
        );
    }
}