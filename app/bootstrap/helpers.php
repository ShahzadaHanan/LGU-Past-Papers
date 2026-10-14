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
    $relative = ltrim($path, '/');
    $absolute = ROOT_PATH . '/public/assets/' . $relative;

    // Cache-bust on the file's own last-modified time so a CSS/JS deploy is
    // picked up immediately instead of waiting on a browser's cache to
    // expire — no manual version bump needed, and unaffected files keep
    // their existing cached copy since their mtime (and so the URL) didn't
    // change.
    $version = is_file($absolute) ? filemtime($absolute) : null;

    return '/assets/' . $relative . ($version ? "?v={$version}" : '');
}

function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}