<?php

declare(strict_types=1);

namespace App\Core;

class Container
{
    private array $services = [];

    public function set(string $key, mixed $service): void
    {
        $this->services[$key] = $service;
    }

    public function get(string $key): mixed
    {
        return $this->services[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->services[$key]);
    }
}