-- Alumni (professional profiles: tenure, degree, current role) and
-- Testimonials (homepage quote carousel) were sharing the `alumni` table.
-- Split them into independent tables with independent admin control.

CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    degree VARCHAR(150) NULL,
    photo VARCHAR(255) NULL,
    quote TEXT NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE alumni DROP COLUMN testimonial;
