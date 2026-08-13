<?php

declare(strict_types=1);

<<<<<<< HEAD
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

=======
>>>>>>> 6fe3e775d7907baf387ac1fad4911d33907d3705
function basePath(string $path = ''): string
{
    return dirname(__DIR__, 2) . ($path ? '/' . ltrim($path, '/') : '');
}

function publicPath(string $path = ''): string
{
    return basePath('public/' . ltrim($path, '/'));
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}