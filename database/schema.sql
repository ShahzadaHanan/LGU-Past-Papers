-- NOTE: This file is out of date and kept only for reference.
-- The authoritative schema lives in database/migrations/*.sql — run
-- `php database/migrate.php` to create all tables, then
-- `php database/seed.php` to load dummy data.

CREATE TABLE admins (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(120) NOT NULL,

    email VARCHAR(190) NOT NULL UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    role ENUM(
        'super_admin',
        'editor'
    ) NOT NULL DEFAULT 'editor',

    last_login TIMESTAMP NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

