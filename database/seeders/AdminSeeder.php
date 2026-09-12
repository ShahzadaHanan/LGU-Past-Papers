<?php

declare(strict_types=1);

/**
 * Fallback only — database/migrate.php already creates the real default
 * admin (admin@lgu.edu.pk / Admin123!) when the admins table is empty, and
 * runs before this seeder in the documented workflow. This file exists so
 * seed.php's own guard has something consistent to fall back to instead of
 * creating a second, differently-credentialed admin account.
 */
$password = password_hash(
    'Admin123!',
    PASSWORD_DEFAULT
);

return [

    'name' => 'LGU Admin',

    'email' => 'admin@lgu.edu.pk',

    'password_hash' => $password,

    'role' => 'super_admin'

];