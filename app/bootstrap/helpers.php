<?php

declare(strict_types=1);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}
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