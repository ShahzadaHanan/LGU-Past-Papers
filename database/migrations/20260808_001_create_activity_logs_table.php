CREATE TABLE activity_logs(

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    admin_id INT UNSIGNED NULL,

    action VARCHAR(100) NOT NULL,

    module VARCHAR(100) NOT NULL,

    record_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    ip_address VARCHAR(45) NULL,

    user_agent TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX(admin_id),

    INDEX(module),

    INDEX(record_id)

);