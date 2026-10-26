-- =====================================================
--  Job Portal Database (Employer + Job Seeker + Admin)
--  MySQL 5.7+ / MariaDB 10.3+
--  ব্যবহার: phpMyAdmin > Import এ এই ফাইল দাও,
--  অথবা: mysql -u root -p < job_portal.sql
-- =====================================================

DROP DATABASE IF EXISTS job_portal;
CREATE DATABASE job_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_portal;

-- -----------------------------------------------------
-- 1. users : সব ইউজারের login (admin / employer / seeker)
-- -----------------------------------------------------
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,              -- password_hash() এর আউটপুট
    role        ENUM('admin','employer','seeker') NOT NULL DEFAULT 'seeker',
    phone       VARCHAR(20)  NULL,
    status      ENUM('active','blocked') NOT NULL DEFAULT 'active',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 2. companies : Employer এর কোম্পানি প্রোফাইল (1 user = 1 company)
-- -----------------------------------------------------
CREATE TABLE companies (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED NOT NULL UNIQUE,
    company_name  VARCHAR(150) NOT NULL,
    logo          VARCHAR(255) NULL,                -- uploads/logos/ এর ফাইলনাম
    website       VARCHAR(255) NULL,
    industry      VARCHAR(100) NULL,
    address       VARCHAR(255) NULL,
    description   TEXT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_companies_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 3. seeker_profiles : Job Seeker এর প্রোফাইল (1 user = 1 profile)
-- -----------------------------------------------------
CREATE TABLE seeker_profiles (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNSIGNED NOT NULL UNIQUE,
    headline          VARCHAR(150) NULL,            -- যেমন: "Junior PHP Developer"
    dob               DATE NULL,
    gender            ENUM('male','female','other') NULL,
    address           VARCHAR(255) NULL,
    photo             VARCHAR(255) NULL,
    resume_file       VARCHAR(255) NULL,            -- uploads/resumes/ এর PDF
    experience_years  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    expected_salary   INT UNSIGNED NULL,
    bio               TEXT NULL,
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_seeker_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 4. categories : জবের ক্যাটাগরি
-- -----------------------------------------------------
CREATE TABLE categories (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL UNIQUE,
    slug  VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 5. jobs : চাকরির পোস্ট
-- -----------------------------------------------------
CREATE TABLE jobs (
    id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id           INT UNSIGNED NOT NULL,
    category_id          INT UNSIGNED NULL,
    title                VARCHAR(150) NOT NULL,
    description          TEXT NOT NULL,
    requirements         TEXT NULL,
    job_type             ENUM('full_time','part_time','remote','internship','contract')
                         NOT NULL DEFAULT 'full_time',
    salary_min           INT UNSIGNED NULL,
    salary_max           INT UNSIGNED NULL,
    location             VARCHAR(100) NULL,
    vacancy              SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    experience_required  TINYINT UNSIGNED NOT NULL DEFAULT 0,   -- বছর
    deadline             DATE NOT NULL,
    status               ENUM('pending','active','closed','rejected') NOT NULL DEFAULT 'pending',
    views                INT UNSIGNED NOT NULL DEFAULT 0,
    created_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_jobs_company  FOREIGN KEY (company_id)
        REFERENCES companies(id) ON DELETE CASCADE,
    CONSTRAINT fk_jobs_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_jobs_status_deadline (status, deadline),
    INDEX idx_jobs_location (location),
    FULLTEXT INDEX ft_jobs_search (title, description)       -- keyword search এর জন্য
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 6. applications : আবেদন (একই জবে একজন একবারই আবেদন করতে পারবে)
-- -----------------------------------------------------
CREATE TABLE applications (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id        INT UNSIGNED NOT NULL,
    seeker_id     INT UNSIGNED NOT NULL,             -- seeker_profiles.id
    cover_letter  TEXT NULL,
    resume_file   VARCHAR(255) NULL,                 -- আবেদনের সময়ের CV কপি
    status        ENUM('applied','shortlisted','interview','rejected','hired')
                  NOT NULL DEFAULT 'applied',
    applied_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_application (job_id, seeker_id),
    CONSTRAINT fk_app_job    FOREIGN KEY (job_id)
        REFERENCES jobs(id) ON DELETE CASCADE,
    CONSTRAINT fk_app_seeker FOREIGN KEY (seeker_id)
        REFERENCES seeker_profiles(id) ON DELETE CASCADE,
    INDEX idx_app_status (status)
) ENGINE=InnoDB;

-- =====================================================
--  EXTRA TABLES
-- =====================================================

-- 7. skills
CREATE TABLE skills (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 8. job_skills : কোন জবে কোন skill লাগবে (many-to-many)
CREATE TABLE job_skills (
    job_id    INT UNSIGNED NOT NULL,
    skill_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (job_id, skill_id),
    CONSTRAINT fk_js_job   FOREIGN KEY (job_id)   REFERENCES jobs(id)   ON DELETE CASCADE,
    CONSTRAINT fk_js_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. seeker_skills : Seeker এর skill (many-to-many)
CREATE TABLE seeker_skills (
    seeker_id  INT UNSIGNED NOT NULL,
    skill_id   INT UNSIGNED NOT NULL,
    PRIMARY KEY (seeker_id, skill_id),
    CONSTRAINT fk_ss_seeker FOREIGN KEY (seeker_id) REFERENCES seeker_profiles(id) ON DELETE CASCADE,
    CONSTRAINT fk_ss_skill  FOREIGN KEY (skill_id)  REFERENCES skills(id)          ON DELETE CASCADE
) ENGINE=InnoDB;

-- 10. educations
CREATE TABLE educations (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seeker_id     INT UNSIGNED NOT NULL,
    degree        VARCHAR(100) NOT NULL,             -- SSC, HSC, BSc ইত্যাদি
    institution   VARCHAR(150) NOT NULL,
    passing_year  YEAR NULL,
    result        VARCHAR(20) NULL,                  -- GPA/CGPA
    CONSTRAINT fk_edu_seeker FOREIGN KEY (seeker_id)
        REFERENCES seeker_profiles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 11. experiences
CREATE TABLE experiences (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seeker_id         INT UNSIGNED NOT NULL,
    company_name      VARCHAR(150) NOT NULL,
    designation       VARCHAR(100) NOT NULL,
    start_date        DATE NOT NULL,
    end_date          DATE NULL,                     -- NULL = বর্তমানে কর্মরত
    responsibilities  TEXT NULL,
    CONSTRAINT fk_exp_seeker FOREIGN KEY (seeker_id)
        REFERENCES seeker_profiles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 12. saved_jobs : Bookmark
CREATE TABLE saved_jobs (
    seeker_id  INT UNSIGNED NOT NULL,
    job_id     INT UNSIGNED NOT NULL,
    saved_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (seeker_id, job_id),
    CONSTRAINT fk_saved_seeker FOREIGN KEY (seeker_id) REFERENCES seeker_profiles(id) ON DELETE CASCADE,
    CONSTRAINT fk_saved_job    FOREIGN KEY (job_id)    REFERENCES jobs(id)            ON DELETE CASCADE
) ENGINE=InnoDB;

-- 13. notifications
CREATE TABLE notifications (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    message     VARCHAR(255) NOT NULL,
    link        VARCHAR(255) NULL,
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notif_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_notif_user_read (user_id, is_read)
) ENGINE=InnoDB;

-- 14. password_resets : Forgot password
CREATE TABLE password_resets (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(150) NOT NULL,
    token       VARCHAR(100) NOT NULL UNIQUE,        -- bin2hex(random_bytes(32))
    expires_at  DATETIME NOT NULL,
    INDEX idx_reset_email (email)
) ENGINE=InnoDB;

-- =====================================================
--  SAMPLE DATA
-- =====================================================

INSERT INTO categories (name, slug) VALUES
('IT & Software',        'it-software'),
('Accounts & Finance',   'accounts-finance'),
('Marketing & Sales',    'marketing-sales'),
('Education & Training', 'education-training'),
('Engineering',          'engineering'),
('Design & Creative',    'design-creative'),
('Customer Support',     'customer-support'),
('NGO & Development',    'ngo-development');

INSERT INTO skills (name) VALUES
('PHP'), ('MySQL'), ('JavaScript'), ('HTML/CSS'), ('Laravel'),
('Bootstrap'), ('MS Excel'), ('Accounting'), ('Communication'),
('Graphic Design'), ('Digital Marketing'), ('English');

-- নোট: Admin অ্যাকাউন্ট বানাতে প্রথমে সাইটের Register ফর্ম দিয়ে
-- সাধারণ অ্যাকাউন্ট খোলো, তারপর নিচের কুয়েরি চালাও
-- (password ঠিকভাবে hash হয়ে থাকবে):
-- UPDATE users SET role = 'admin' WHERE email = 'tomar@email.com';

-- =====================================================
--  কাজের কিছু QUERY (PHP তে prepared statement এ ব্যবহার করবে)
-- =====================================================

-- হোমপেজে active ও deadline পার হয়নি এমন জব:
-- SELECT j.*, c.company_name, c.logo, cat.name AS category
-- FROM jobs j
-- JOIN companies c ON c.id = j.company_id
-- LEFT JOIN categories cat ON cat.id = j.category_id
-- WHERE j.status = 'active' AND j.deadline >= CURDATE()
-- ORDER BY j.created_at DESC
-- LIMIT 10 OFFSET 0;

-- Keyword search:
-- ... WHERE MATCH(j.title, j.description) AGAINST (? IN NATURAL LANGUAGE MODE)

-- Employer তার একটি জবের আবেদনকারী দেখবে:
-- SELECT a.*, u.name, u.email, sp.headline, sp.experience_years
-- FROM applications a
-- JOIN seeker_profiles sp ON sp.id = a.seeker_id
-- JOIN users u ON u.id = sp.user_id
-- WHERE a.job_id = ?
-- ORDER BY a.applied_at DESC;

-- Seeker তার আবেদনের status দেখবে:
-- SELECT a.status, a.applied_at, j.title, c.company_name
-- FROM applications a
-- JOIN jobs j ON j.id = a.job_id
-- JOIN companies c ON c.id = j.company_id
-- WHERE a.seeker_id = ?
-- ORDER BY a.applied_at DESC;
