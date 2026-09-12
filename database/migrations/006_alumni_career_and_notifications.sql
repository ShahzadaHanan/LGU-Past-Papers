-- Split alumni's professional-profile fields from their testimonial quote,
-- and generalize notification_log so subscriber updates can cover papers,
-- videos and announcements, not only papers.

ALTER TABLE alumni
    ADD COLUMN tenure_start_year VARCHAR(10) NULL AFTER batch_year,
    ADD COLUMN current_position VARCHAR(150) NULL AFTER degree,
    ADD COLUMN current_field VARCHAR(150) NULL AFTER current_position;

ALTER TABLE notification_log
    MODIFY paper_id INT UNSIGNED NULL,
    ADD COLUMN type VARCHAR(20) NOT NULL DEFAULT 'paper' AFTER paper_id,
    ADD COLUMN title VARCHAR(255) NULL AFTER type,
    ADD COLUMN url VARCHAR(255) NULL AFTER title;
