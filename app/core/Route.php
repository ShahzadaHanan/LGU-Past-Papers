<?php

declare(strict_types=1);

namespace App\Core;

class Route
{
    public array $parameters = [];

    public function __construct(
        public string $method,
        public string $uri,
        public string $controller,
        public string $action,
        public ?string $name = null,
        public array $middleware = []
    ) {
    }
}
