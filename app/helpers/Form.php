<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Core\Csrf;

class Form
{
    public static function csrf(
        Csrf $csrf
    ): string {

        return sprintf(
            '<input type="hidden" name="_token" value="%s">',
            $csrf->token()
        );

    }
}