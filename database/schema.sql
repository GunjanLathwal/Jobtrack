CREATE DATABASE IF NOT EXISTS jobtrack
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE jobtrack;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    job_url VARCHAR(500) NULL,
    location VARCHAR(150) NULL,
    employment_type ENUM('Full-time','Part-time','Contract','Internship','Temporary','Other') NOT NULL DEFAULT 'Full-time',
    salary_min DECIMAL(12,2) NULL,
    salary_max DECIMAL(12,2) NULL,
    date_applied DATE NULL,
    status ENUM('Wishlist','Applied','Assessment','Interview','Offer','Rejected','Withdrawn') NOT NULL DEFAULT 'Wishlist',
    recruiter_name VARCHAR(120) NULL,
    recruiter_email VARCHAR(190) NULL,
    notes TEXT NULL,
    follow_up_date DATE NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_applications_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_applications_user_status (user_id, status),
    INDEX idx_applications_user_date (user_id, date_applied),
    INDEX idx_applications_user_followup (user_id, follow_up_date),
    INDEX idx_applications_user_company (user_id, company_name),
    INDEX idx_applications_user_title (user_id, job_title)
) ENGINE=InnoDB;

CREATE TABLE application_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id BIGINT UNSIGNED NOT NULL,
    event_type VARCHAR(80) NOT NULL,
    event_date DATE NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_events_application
        FOREIGN KEY (application_id) REFERENCES applications(id)
        ON DELETE CASCADE,

    INDEX idx_events_application_date (application_id, event_date)
) ENGINE=InnoDB;
