<?php

declare(strict_types=1);

$password = password_hash(
    'admin123',
    PASSWORD_DEFAULT
);

return [

    'name' => 'Administrator',

    'email' => 'admin@lgu.local',

    'password_hash' => $password,

    'role' => 'super_admin'

];