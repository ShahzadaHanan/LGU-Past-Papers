-- Create subjects table — the course catalogue for a degree (sub_department),
-- managed independently from papers.subject_name (free text on each paper).
CREATE TABLE IF NOT EXISTS subjects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sub_department_id INT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(30) NULL,
    semester TINYINT UNSIGNED NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_subject_per_degree (sub_department_id, name),
    FOREIGN KEY (sub_department_id) REFERENCES sub_departments(id) ON DELETE CASCADE
);
