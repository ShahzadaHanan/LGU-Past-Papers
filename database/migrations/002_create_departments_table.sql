CREATE TABLE IF NOT EXISTS departments (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,

    slug VARCHAR(180) NOT NULL UNIQUE,

    hero_image VARCHAR(255),

    description LONGTEXT,

    meta_title VARCHAR(255),

    meta_description TEXT,

    display_order INT DEFAULT 0,

    is_active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);