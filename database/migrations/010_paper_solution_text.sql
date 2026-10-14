-- Lets a paper's solution be plain text (typed by an admin) in addition to
-- (or instead of) an uploaded solution image.

ALTER TABLE papers
    ADD COLUMN solution_text TEXT NULL AFTER solution_image;
