-- Links an approved public paper submission to the actual Paper record it
-- was published as, so the admin can't double-publish the same submission
-- and can jump straight to the live paper from the submissions list.

ALTER TABLE paper_submissions
    ADD COLUMN published_paper_id INT UNSIGNED NULL AFTER status,
    ADD CONSTRAINT fk_paper_submissions_paper FOREIGN KEY (published_paper_id) REFERENCES papers(id) ON DELETE SET NULL;
