<?php

declare(strict_types=1);

namespace App\Helpers;

class Slug
{
    public static function make(
        string $text
    ):string{

        $text=strtolower($text);

        $text=preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $text
        );

        return trim(
            $text,
            '-'
        );

    }
}