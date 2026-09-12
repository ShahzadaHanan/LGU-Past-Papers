-- Site-wide news/announcement bar, admin-managed (sequence, active/inactive, delete).
CREATE TABLE IF NOT EXISTS announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL,
    link_url VARCHAR(255) NULL,
    link_label VARCHAR(100) NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
