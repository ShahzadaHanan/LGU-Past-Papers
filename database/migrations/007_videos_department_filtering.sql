-- Lets lectures be filtered by Department -> Degree -> Subject instead of
-- the old flat video_categories tabs (video_categories/category_id stay,
-- untouched, for whatever still reads them).

ALTER TABLE videos
    ADD COLUMN sub_department_id INT UNSIGNED NULL AFTER category_id,
    ADD COLUMN subject_name VARCHAR(150) NULL AFTER sub_department_id,
    ADD CONSTRAINT fk_videos_sub_department FOREIGN KEY (sub_department_id) REFERENCES sub_departments(id) ON DELETE SET NULL;
