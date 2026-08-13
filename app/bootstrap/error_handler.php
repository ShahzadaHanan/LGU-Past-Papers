<?php

declare(strict_types=1);

error_reporting(E_ALL);

ini_set('display_errors', env('APP_DEBUG') ? '1' : '0');

ini_set('log_errors', '1');

ini_set(
    'error_log',
    basePath('storage/logs/php-error.log')
);