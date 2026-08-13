-- Create sub_departments table
CREATE TABLE IF NOT EXISTS sub_departments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- Create papers table
CREATE TABLE IF NOT EXISTS papers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sub_department_id INT UNSIGNED NOT NULL,
    exam_type ENUM('mids', 'finals', 'summer_mids', 'summer_finals') NOT NULL,
    session VARCHAR(20) NOT NULL,
    subject_name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    paper_image VARCHAR(255) NOT NULL,
    solution_image VARCHAR(255) NULL,
    download_file VARCHAR(255) NULL,
    views_count INT DEFAULT 0,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sub_department_id) REFERENCES sub_departments(id) ON DELETE CASCADE
);

-- Create hero_slides table
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) NULL,
    link_url VARCHAR(255) NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create content_blocks table
CREATE TABLE IF NOT EXISTS content_blocks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(100) NOT NULL,
    heading VARCHAR(255) NOT NULL,
    body LONGTEXT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create video_categories table
CREATE TABLE IF NOT EXISTS video_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create videos table
CREATE TABLE IF NOT EXISTS videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    youtube_url VARCHAR(255) NOT NULL,
    youtube_video_id VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES video_categories(id) ON DELETE CASCADE
);

-- Create class_bookings table
CREATE TABLE IF NOT EXISTS class_bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    whatsapp VARCHAR(50) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    topic VARCHAR(150) NULL,
    fee_type ENUM('full_subject', 'single_lecture') NOT NULL,
    description TEXT NULL,
    status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create newsletter_subscribers table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_verified TINYINT(1) DEFAULT 0,
    unsubscribe_token VARCHAR(100) NULL
);

-- Create notification_log table
CREATE TABLE IF NOT EXISTS notification_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paper_id INT UNSIGNED NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    recipients_count INT DEFAULT 0,
    FOREIGN KEY (paper_id) REFERENCES papers(id) ON DELETE CASCADE
);

-- Create paper_submissions table
CREATE TABLE IF NOT EXISTS paper_submissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    whatsapp VARCHAR(50) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    description TEXT NULL,
    image_path VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create alumni table
CREATE TABLE IF NOT EXISTS alumni (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    batch_year VARCHAR(10) NOT NULL,
    degree VARCHAR(150) NOT NULL,
    photo VARCHAR(255) NULL,
    testimonial TEXT NOT NULL,
    linkedin_url VARCHAR(255) NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create site_settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL
);
