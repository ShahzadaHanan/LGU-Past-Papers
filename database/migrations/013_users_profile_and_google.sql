-- Extend public site accounts with the fields the signup dialog collects
-- (phone, profile picture) and Google sign-in support. password_hash
-- becomes nullable since a Google-only account never sets a local password.
ALTER TABLE users
    MODIFY password_hash VARCHAR(255) NULL,
    ADD COLUMN phone VARCHAR(30) NULL AFTER email,
    ADD COLUMN profile_image VARCHAR(255) NULL AFTER password_hash,
    ADD COLUMN auth_provider VARCHAR(20) NOT NULL DEFAULT 'local' AFTER profile_image,
    ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER auth_provider;
