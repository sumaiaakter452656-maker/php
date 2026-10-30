-- =====================================================================
--  JOBS PORTAL  -  DATABASE SCHEMA  (18 tables)
--  Built for : PHP + MySQL + HTML + CSS + JavaScript
--  Database  : MySQL 8.0
--  Roles     : admin, employer, seeker
--
--  Import (Laragon): HeidiSQL -> File -> Run SQL file,  or  phpMyAdmin -> Import
--
--  Tables
--    Users & profiles : users, companies, seekers
--    Lookup lists     : cities, categories, skills
--    Seeker CV        : seeker_educations, seeker_experiences, seeker_skills
--    Packages         : packages, subscriptions
--    Jobs             : jobs, job_skills
--    Applying         : job_applications, saved_jobs
--    Payments         : payment_methods, payment_transactions
--    Other            : notifications
-- =====================================================================

DROP DATABASE IF EXISTS jobs_portal;
CREATE DATABASE jobs_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jobs_portal;


-- =====================================================================
-- 1. USERS  -  one login table for admin, employer and seeker
-- =====================================================================

CREATE TABLE users (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role              ENUM('admin','employer','seeker') NOT NULL,
    name              VARCHAR(120) NOT NULL,
    email             VARCHAR(150) NOT NULL UNIQUE,
    phone             VARCHAR(20)  NULL,
    password          VARCHAR(255) NOT NULL,            -- password_hash() output
    photo             VARCHAR(255) NULL,
    reset_token       CHAR(64)     NULL,                -- for "Forgot password"
    reset_expires_at  DATETIME     NULL,
    status            ENUM('active','blocked') NOT NULL DEFAULT 'active',
    last_login_at     DATETIME     NULL,
    created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role, status)
) ENGINE=InnoDB;


-- =====================================================================
-- 2. LOOKUP LISTS  -  managed by admin, used in dropdowns
-- =====================================================================

-- City / district with its division
CREATE TABLE cities (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    division    VARCHAR(50)  NOT NULL,                  -- Dhaka, Chattogram ...
    name        VARCHAR(100) NOT NULL,
    is_active   TINYINT(1)   NOT NULL DEFAULT 1,
    UNIQUE KEY uq_cities (division, name)
) ENGINE=InnoDB;

-- Job category: Software Engineer, Accounts, Sales ...
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120) NOT NULL UNIQUE,
    slug        VARCHAR(140) NOT NULL UNIQUE,
    is_popular  TINYINT(1)   NOT NULL DEFAULT 0,        -- show on the homepage
    sort_order  SMALLINT     NOT NULL DEFAULT 0,
    is_active   TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE skills (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(80)  NOT NULL UNIQUE,
    is_active   TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB;


-- =====================================================================
-- 3. PROFILES
-- =====================================================================

-- Employer's company (1 employer user = 1 company)
CREATE TABLE companies (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNSIGNED NOT NULL UNIQUE,
    name              VARCHAR(150) NOT NULL,
    slug              VARCHAR(170) NOT NULL UNIQUE,     -- company page URL
    industry          VARCHAR(100) NULL,                -- IT, Garments, Banking ...
    description       TEXT         NULL,
    logo              VARCHAR(255) NULL,
    employee_count    VARCHAR(20)  NULL,                -- 1-10, 11-50, 51-200, 500+
    established_year  YEAR         NULL,
    email             VARCHAR(150) NULL,
    phone             VARCHAR(20)  NULL,
    website           VARCHAR(255) NULL,
    city_id           INT UNSIGNED NULL,
    address           VARCHAR(255) NULL,
    is_verified       TINYINT(1)   NOT NULL DEFAULT 0,  -- checked by admin
    is_featured       TINYINT(1)   NOT NULL DEFAULT 0,  -- "Top Companies"
    created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_companies_user FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_companies_city FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Job seeker's profile (1 seeker user = 1 profile)
CREATE TABLE seekers (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNSIGNED NOT NULL UNIQUE,
    headline          VARCHAR(150) NULL,                -- "Junior PHP Developer"
    date_of_birth     DATE         NULL,
    gender            ENUM('male','female','other') NULL,
    city_id           INT UNSIGNED NULL,
    address           VARCHAR(255) NULL,
    category_id       INT UNSIGNED NULL,                -- preferred job category
    experience_years  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    current_salary    DECIMAL(12,2) NULL,
    expected_salary   DECIMAL(12,2) NULL,
    career_summary    TEXT         NULL,
    resume_file       VARCHAR(255) NULL,                -- uploads/resumes/xxxx.pdf
    is_public         TINYINT(1)   NOT NULL DEFAULT 1,  -- visible to employers
    created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_seekers_user     FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
    CONSTRAINT fk_seekers_city     FOREIGN KEY (city_id)     REFERENCES cities(id)     ON DELETE SET NULL,
    CONSTRAINT fk_seekers_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;


-- =====================================================================
-- 4. SEEKER CV DETAILS
-- =====================================================================

CREATE TABLE seeker_educations (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seeker_id     INT UNSIGNED NOT NULL,
    level         ENUM('ssc','hsc','diploma','bachelor','masters','phd') NOT NULL,
    degree_title  VARCHAR(150) NOT NULL,                -- BSc in CSE
    institution   VARCHAR(180) NOT NULL,
    passing_year  YEAR         NULL,
    result        VARCHAR(20)  NULL,                    -- GPA 5.00, CGPA 3.75
    CONSTRAINT fk_edu_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE seeker_experiences (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seeker_id         INT UNSIGNED NOT NULL,
    company_name      VARCHAR(150) NOT NULL,
    designation       VARCHAR(150) NOT NULL,
    start_date        DATE         NOT NULL,
    end_date          DATE         NULL,                -- NULL = still working here
    responsibilities  TEXT         NULL,
    CONSTRAINT fk_exp_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seeker <-> Skill  (many-to-many)
CREATE TABLE seeker_skills (
    seeker_id  INT UNSIGNED NOT NULL,
    skill_id   INT UNSIGNED NOT NULL,
    PRIMARY KEY (seeker_id, skill_id),
    CONSTRAINT fk_ss_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE,
    CONSTRAINT fk_ss_skill  FOREIGN KEY (skill_id)  REFERENCES skills(id)  ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================================================
-- 5. PACKAGES & SUBSCRIPTIONS  (created before jobs, because jobs uses them)
-- =====================================================================

CREATE TABLE packages (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(100)  NOT NULL,
    description         VARCHAR(255)  NULL,
    price               DECIMAL(12,2) NOT NULL DEFAULT 0.00,   -- 0 = free
    duration_days       SMALLINT UNSIGNED NOT NULL DEFAULT 30,
    job_post_limit      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    featured_job_limit  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_popular          TINYINT(1)    NOT NULL DEFAULT 0,
    is_active           TINYINT(1)    NOT NULL DEFAULT 1,
    sort_order          SMALLINT      NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- A package bought by an employer, with the quota left.
-- Starts as 'pending' and becomes 'active' when its payment is approved.
CREATE TABLE subscriptions (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED  NOT NULL,
    package_id          INT UNSIGNED  NOT NULL,
    price               DECIMAL(12,2) NOT NULL,         -- price at the time of purchase
    job_posts_left      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    featured_jobs_left  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    starts_at           DATETIME      NULL,
    ends_at             DATETIME      NULL,
    status              ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'pending',
    created_at          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_subs_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    CONSTRAINT fk_subs_package FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE RESTRICT,
    INDEX idx_subs_active (user_id, status, ends_at)
) ENGINE=InnoDB;


-- =====================================================================
-- 6. JOBS
-- =====================================================================

CREATE TABLE jobs (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id        INT UNSIGNED  NOT NULL,
    category_id       INT UNSIGNED  NULL,
    subscription_id   INT UNSIGNED  NULL,               -- package quota used for this post
    title             VARCHAR(180)  NOT NULL,
    slug              VARCHAR(200)  NOT NULL UNIQUE,
    description       TEXT          NOT NULL,
    requirements      TEXT          NULL,
    benefits          TEXT          NULL,
    job_type          ENUM('full_time','part_time','contract','internship','remote') NOT NULL DEFAULT 'full_time',
    career_level      ENUM('entry','mid','senior','manager') NOT NULL DEFAULT 'entry',
    experience_min    TINYINT UNSIGNED NOT NULL DEFAULT 0,    -- years
    education         VARCHAR(150)  NULL,               -- "Bachelor in any discipline"
    gender            ENUM('any','male','female') NOT NULL DEFAULT 'any',
    city_id           INT UNSIGNED  NULL,
    vacancies         SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    salary_min        DECIMAL(12,2) NULL,
    salary_max        DECIMAL(12,2) NULL,
    is_negotiable     TINYINT(1)    NOT NULL DEFAULT 0, -- show "Negotiable" instead of salary
    deadline          DATE          NOT NULL,
    status            ENUM('pending','active','closed','rejected') NOT NULL DEFAULT 'pending',
    reject_reason     VARCHAR(255)  NULL,
    is_featured       TINYINT(1)    NOT NULL DEFAULT 0,
    featured_until    DATETIME      NULL,
    views             INT UNSIGNED  NOT NULL DEFAULT 0,
    created_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_jobs_company      FOREIGN KEY (company_id)      REFERENCES companies(id)     ON DELETE CASCADE,
    CONSTRAINT fk_jobs_category     FOREIGN KEY (category_id)     REFERENCES categories(id)    ON DELETE SET NULL,
    CONSTRAINT fk_jobs_subscription FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE SET NULL,
    CONSTRAINT fk_jobs_city         FOREIGN KEY (city_id)         REFERENCES cities(id)        ON DELETE SET NULL,
    INDEX idx_jobs_listing (status, deadline),
    INDEX idx_jobs_filter (category_id, city_id, job_type),
    FULLTEXT INDEX ft_jobs_search (title, description)
) ENGINE=InnoDB;

-- Job <-> Skill  (many-to-many)
CREATE TABLE job_skills (
    job_id    INT UNSIGNED NOT NULL,
    skill_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (job_id, skill_id),
    CONSTRAINT fk_js_job   FOREIGN KEY (job_id)   REFERENCES jobs(id)   ON DELETE CASCADE,
    CONSTRAINT fk_js_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================================================
-- 7. APPLYING
-- =====================================================================

CREATE TABLE job_applications (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id           INT UNSIGNED  NOT NULL,
    seeker_id        INT UNSIGNED  NOT NULL,
    cover_letter     TEXT          NULL,
    expected_salary  DECIMAL(12,2) NULL,
    resume_file      VARCHAR(255)  NULL,                -- copy of the CV used for this job
    status           ENUM('applied','viewed','shortlisted','interview','hired','rejected')
                     NOT NULL DEFAULT 'applied',
    interview_at     DATETIME      NULL,
    employer_note    TEXT          NULL,                -- private, seeker cannot see it
    applied_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_apply_once (job_id, seeker_id),       -- one application per job
    CONSTRAINT fk_app_job    FOREIGN KEY (job_id)    REFERENCES jobs(id)    ON DELETE CASCADE,
    CONSTRAINT fk_app_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE,
    INDEX idx_app_status (status)
) ENGINE=InnoDB;

-- Seeker bookmarks a job
CREATE TABLE saved_jobs (
    seeker_id   INT UNSIGNED NOT NULL,
    job_id      INT UNSIGNED NOT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (seeker_id, job_id),
    CONSTRAINT fk_sj_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE,
    CONSTRAINT fk_sj_job    FOREIGN KEY (job_id)    REFERENCES jobs(id)    ON DELETE CASCADE
) ENGINE=InnoDB;


-- =====================================================================
-- 8. PAYMENTS
--
--    payment_methods  1 --< many  payment_transactions
--    subscriptions    1 --< many  payment_transactions
--    users            1 --< many  payment_transactions
--
--    Flow (manual: Cash / bKash / Nagad / Rocket / Bank):
--      1. Employer picks a package  -> subscriptions row        (pending)
--      2. Employer pays and sends sender number + TrxID / slip
--                                   -> payment_transactions row (pending)
--      3. Admin checks and approves -> transaction = completed,
--                                      subscription = active
-- =====================================================================

CREATE TABLE payment_methods (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(60)   NOT NULL UNIQUE,     -- shown to the user: "bKash"
    code             VARCHAR(30)   NOT NULL UNIQUE,     -- used in PHP: 'bkash'
    type             ENUM('cash','mobile_banking','bank','card') NOT NULL,
    account_name     VARCHAR(120)  NULL,                -- "Jobs Portal Ltd."
    account_number   VARCHAR(60)   NULL,                -- bKash/Nagad number or bank A/C
    bank_name        VARCHAR(120)  NULL,
    branch_name      VARCHAR(120)  NULL,
    instructions     TEXT          NULL,                -- shown on the payment page
    charge_percent   DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
    needs_trx_id     TINYINT(1)    NOT NULL DEFAULT 0,  -- ask for sender number + TrxID
    needs_proof      TINYINT(1)    NOT NULL DEFAULT 0,  -- ask for slip / screenshot
    is_active        TINYINT(1)    NOT NULL DEFAULT 1,
    sort_order       SMALLINT      NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE payment_transactions (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transaction_no     VARCHAR(30)   NOT NULL UNIQUE,   -- our invoice no: TXN-20261030-0001
    user_id            INT UNSIGNED  NOT NULL,          -- who paid
    subscription_id    INT UNSIGNED  NOT NULL,          -- which package purchase
    payment_method_id  INT UNSIGNED  NOT NULL,          -- Cash / bKash / Nagad / Bank / Card
    amount             DECIMAL(12,2) NOT NULL,          -- package price
    charge             DECIMAL(12,2) NOT NULL DEFAULT 0.00,  -- method charge (e.g. bKash %)
    total_amount       DECIMAL(12,2) NOT NULL,          -- amount + charge
    sender_number      VARCHAR(30)   NULL,              -- payer's bKash/Nagad no. or bank A/C
    trx_id             VARCHAR(100)  NULL,              -- TrxID / bank reference
    proof_file         VARCHAR(255)  NULL,              -- uploads/payments/slip.jpg
    status             ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
    admin_note         VARCHAR(255)  NULL,              -- e.g. why it was rejected
    verified_by        INT UNSIGNED  NULL,              -- admin users.id
    verified_at        DATETIME      NULL,
    created_at         TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pt_user         FOREIGN KEY (user_id)           REFERENCES users(id)           ON DELETE RESTRICT,
    CONSTRAINT fk_pt_subscription FOREIGN KEY (subscription_id)   REFERENCES subscriptions(id)   ON DELETE RESTRICT,
    CONSTRAINT fk_pt_method       FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE RESTRICT,
    CONSTRAINT fk_pt_verifier     FOREIGN KEY (verified_by)       REFERENCES users(id)           ON DELETE SET NULL,
    UNIQUE KEY uq_pt_trx (payment_method_id, trx_id),   -- the same TrxID cannot be used twice
    INDEX idx_pt_status (status, created_at)
) ENGINE=InnoDB;


-- =====================================================================
-- 9. NOTIFICATIONS
-- =====================================================================

CREATE TABLE notifications (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    message     VARCHAR(255) NOT NULL,                  -- "You have been shortlisted for ..."
    link        VARCHAR(255) NULL,
    is_read     TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_notif_unread (user_id, is_read)
) ENGINE=InnoDB;


-- =====================================================================
-- 10. SEED DATA
-- =====================================================================

-- Default admin login  (change the password after the first login!)
--   Email    : admin@jobsportal.test
--   Password : Admin@12345
INSERT INTO users (role, name, email, password) VALUES
('admin', 'Site Admin', 'admin@jobsportal.test',
 '$2y$10$l3.94gcsSqma3DPqbSwWHO9.QYdqE0D1mfVretncbX.Mve/OpLV2q');

INSERT INTO cities (division, name) VALUES
('Dhaka', 'Dhaka'), ('Dhaka', 'Gazipur'), ('Dhaka', 'Narayanganj'),
('Chattogram', 'Chattogram'), ('Chattogram', 'Cumilla'),
('Rajshahi', 'Rajshahi'), ('Khulna', 'Khulna'), ('Barishal', 'Barishal'),
('Sylhet', 'Sylhet'), ('Rangpur', 'Rangpur'), ('Mymensingh', 'Mymensingh');

INSERT INTO categories (name, slug, is_popular, sort_order) VALUES
('Software Engineer',   'software-engineer',   1, 1),
('Accounts / Finance',  'accounts-finance',    1, 2),
('HR / Admin',          'hr-admin',            1, 3),
('Sales',               'sales',               1, 4),
('Marketing',           'marketing',           1, 5),
('Teacher / Education', 'teacher-education',   1, 6),
('Engineering',         'engineering',         0, 7),
('Graphic Designer',    'graphic-designer',    0, 8),
('Customer Support',    'customer-support',    0, 9),
('Healthcare',          'healthcare',          0, 10);

INSERT INTO skills (name) VALUES
('PHP'), ('MySQL'), ('HTML'), ('CSS'), ('JavaScript'), ('MS Excel'),
('Accounting'), ('Communication'), ('Graphic Design'), ('Digital Marketing'),
('English Writing'), ('Customer Service');

INSERT INTO packages (name, description, price, duration_days, job_post_limit, featured_job_limit, is_popular, sort_order) VALUES
('Free',     'Try the portal',          0.00, 30,  1,  0, 0, 1),
('Standard', 'For small companies',  1500.00, 30, 10,  2, 1, 2),
('Premium',  'For large companies',  4000.00, 90, 50, 10, 0, 3);

-- Replace the numbers / account details with your real ones
INSERT INTO payment_methods
(name, code, type, account_name, account_number, bank_name, branch_name, instructions,
 charge_percent, needs_trx_id, needs_proof, is_active, sort_order)
VALUES
('Cash', 'cash', 'cash', NULL, NULL, NULL, NULL,
 'Pay in cash at our office. Your package will be activated after admin confirmation.',
 0.00, 0, 0, 1, 1),
('bKash', 'bkash', 'mobile_banking', 'Jobs Portal', '01XXXXXXXXX', NULL, NULL,
 'Make a Payment to 01XXXXXXXXX from your bKash app, then enter your bKash number and the TrxID.',
 1.85, 1, 0, 1, 2),
('Nagad', 'nagad', 'mobile_banking', 'Jobs Portal', '01XXXXXXXXX', NULL, NULL,
 'Make a Payment to 01XXXXXXXXX from your Nagad app, then enter your Nagad number and the TrxID.',
 1.50, 1, 0, 1, 3),
('Rocket', 'rocket', 'mobile_banking', 'Jobs Portal', '01XXXXXXXXXX', NULL, NULL,
 'Send the amount to the Rocket number above, then enter your Rocket number and the TrxID.',
 1.80, 1, 0, 1, 4),
('Bank Transfer', 'bank_transfer', 'bank', 'Jobs Portal Ltd.', '1234567890123', 'Example Bank PLC', 'Motijheel Branch',
 'Deposit or transfer to the bank account above, then enter the reference number and upload the slip.',
 0.00, 1, 1, 1, 5),
('Card (Visa / Mastercard)', 'card', 'card', NULL, NULL, NULL, NULL,
 'Pay online with your debit or credit card. (Turned off until an online payment gateway is connected.)',
 2.50, 0, 0, 0, 6);


-- =====================================================================
-- 11. USEFUL QUERIES  (use them with prepared statements in PHP)
-- =====================================================================

-- Homepage: latest open jobs, featured first
-- SELECT j.id, j.title, j.slug, j.job_type, j.salary_min, j.salary_max, j.is_negotiable,
--        j.deadline, c.name AS company, c.logo, ci.name AS city
-- FROM jobs j
-- JOIN companies c    ON c.id  = j.company_id
-- LEFT JOIN cities ci ON ci.id = j.city_id
-- WHERE j.status = 'active' AND j.deadline >= CURDATE()
-- ORDER BY j.is_featured DESC, j.created_at DESC
-- LIMIT 10;

-- Number of open jobs in each category
-- SELECT cat.id, cat.name, COUNT(j.id) AS total
-- FROM categories cat
-- LEFT JOIN jobs j ON j.category_id = cat.id
--                 AND j.status = 'active' AND j.deadline >= CURDATE()
-- WHERE cat.is_active = 1
-- GROUP BY cat.id, cat.name
-- ORDER BY total DESC;

-- Employer: applicants of one job
-- SELECT a.id, a.status, a.applied_at, a.resume_file, u.name, u.email, s.headline
-- FROM job_applications a
-- JOIN seekers s ON s.id = a.seeker_id
-- JOIN users u   ON u.id = s.user_id
-- WHERE a.job_id = ?
-- ORDER BY a.applied_at DESC;

-- Admin: payments waiting for approval
-- SELECT pt.id, pt.transaction_no, u.name, pm.name AS method,
--        pt.sender_number, pt.trx_id, pt.proof_file, pt.total_amount, pt.created_at
-- FROM payment_transactions pt
-- JOIN users u            ON u.id  = pt.user_id
-- JOIN payment_methods pm ON pm.id = pt.payment_method_id
-- WHERE pt.status = 'pending'
-- ORDER BY pt.created_at;

-- Admin: approve a payment (run both inside one transaction)
-- UPDATE payment_transactions
--    SET status = 'completed', verified_by = ?, verified_at = NOW()
--  WHERE id = ? AND status = 'pending';
-- UPDATE subscriptions s
--   JOIN packages p ON p.id = s.package_id
--    SET s.status = 'active',
--        s.starts_at = NOW(),
--        s.ends_at = NOW() + INTERVAL p.duration_days DAY,
--        s.job_posts_left = p.job_post_limit,
--        s.featured_jobs_left = p.featured_job_limit
--  WHERE s.id = ?;

-- Revenue by payment method (this month)
-- SELECT pm.name, COUNT(*) AS payments, SUM(pt.total_amount) AS revenue
-- FROM payment_transactions pt
-- JOIN payment_methods pm ON pm.id = pt.payment_method_id
-- WHERE pt.status = 'completed'
--   AND pt.verified_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
-- GROUP BY pm.id, pm.name
-- ORDER BY revenue DESC;
