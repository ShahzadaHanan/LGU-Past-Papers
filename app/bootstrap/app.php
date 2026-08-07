<?php

declare(strict_types=1);

use App\Core\App;
use Dotenv\Dotenv;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

date_default_timezone_set('Asia/Karachi');

session_start();

$app = new App();

$app->boot();

$app->run();