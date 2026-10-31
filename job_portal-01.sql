-- =====================================================================
-- Jobs Portal – Database Schema (MySQL 8 / MariaDB 10.5+)
-- 20 tables in 7 groups
-- Trainee: Sumaia Akter · ID 1297731 · Batch PWAD/USSL-M/73/01
-- =====================================================================

CREATE DATABASE IF NOT EXISTS jobs_portal
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jobs_portal;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifications, payment_transactions, payment_methods,
  subscriptions, packages, saved_jobs, job_applications, job_skills, jobs,
  seeker_cv_items, seeker_skills, seeker_experiences, seeker_educations,
  seekers, companies, cv_templates, skills, categories, cities, users;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- Group 2: Lookup lists (managed by the admin)
-- ---------------------------------------------------------------------
CREATE TABLE cities (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  division    VARCHAR(50)  NOT NULL,
  UNIQUE KEY uq_city (name, division)
) ENGINE=InnoDB;

CREATE TABLE categories (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  slug        VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE skills (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(80)  NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE cv_templates (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  thumbnail   VARCHAR(255) NULL,
  layout_file VARCHAR(255) NOT NULL,
  is_premium  BOOLEAN      NOT NULL DEFAULT FALSE,
  status      ENUM('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Group 1: Users and profiles
-- ---------------------------------------------------------------------
CREATE TABLE users (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  email       VARCHAR(150) NOT NULL UNIQUE,
  phone       VARCHAR(20)  NULL,
  password    VARCHAR(255) NOT NULL,              -- password_hash()
  role        ENUM('admin','employer','seeker') NOT NULL,
  status      ENUM('active','pending','blocked') NOT NULL DEFAULT 'active',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE companies (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL UNIQUE,       -- one employer = one company
  city_id     INT UNSIGNED NULL,
  name        VARCHAR(150) NOT NULL,
  logo        VARCHAR(255) NULL,
  website     VARCHAR(255) NULL,
  address     TEXT NULL,
  description TEXT NULL,
  CONSTRAINT fk_companies_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_companies_city FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE seekers (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id          INT UNSIGNED NOT NULL UNIQUE,  -- one seeker = one profile
  city_id          INT UNSIGNED NULL,
  category_id      INT UNSIGNED NULL,
  cv_template_id   INT UNSIGNED NULL,
  headline         VARCHAR(150) NULL,
  summary          TEXT NULL,
  experience_years TINYINT UNSIGNED NOT NULL DEFAULT 0,
  expected_salary  DECIMAL(10,2) NULL,
  resume_file      VARCHAR(255) NULL,             -- built or uploaded CV (PDF)
  CONSTRAINT fk_seekers_user     FOREIGN KEY (user_id)        REFERENCES users(id)        ON DELETE CASCADE,
  CONSTRAINT fk_seekers_city     FOREIGN KEY (city_id)        REFERENCES cities(id)       ON DELETE SET NULL,
  CONSTRAINT fk_seekers_category FOREIGN KEY (category_id)    REFERENCES categories(id)   ON DELETE SET NULL,
  CONSTRAINT fk_seekers_template FOREIGN KEY (cv_template_id) REFERENCES cv_templates(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Group 3: Seeker CV (CV builder)
-- ---------------------------------------------------------------------
CREATE TABLE seeker_educations (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seeker_id    INT UNSIGNED NOT NULL,
  level        VARCHAR(50)  NOT NULL,             -- SSC, HSC, Bachelor, Masters
  degree_title VARCHAR(150) NOT NULL,
  institution  VARCHAR(150) NOT NULL,
  result       VARCHAR(30)  NULL,
  year         YEAR NULL,
  CONSTRAINT fk_edu_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE seeker_experiences (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seeker_id    INT UNSIGNED NOT NULL,
  company_name VARCHAR(150) NOT NULL,
  designation  VARCHAR(100) NOT NULL,
  start_date   DATE NOT NULL,
  end_date     DATE NULL,
  is_current   BOOLEAN NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_exp_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE seeker_skills (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seeker_id  INT UNSIGNED NOT NULL,
  skill_id   INT UNSIGNED NOT NULL,
  UNIQUE KEY uq_seeker_skill (seeker_id, skill_id),
  CONSTRAINT fk_ss_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE,
  CONSTRAINT fk_ss_skill  FOREIGN KEY (skill_id)  REFERENCES skills(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- Projects, certificates and languages in one table (type column)
CREATE TABLE seeker_cv_items (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seeker_id    INT UNSIGNED NOT NULL,
  type         ENUM('project','certificate','language') NOT NULL,
  title        VARCHAR(150) NOT NULL,             -- project / certificate / language name
  organization VARCHAR(150) NULL,                 -- issuer or client
  level        VARCHAR(50)  NULL,                 -- e.g. language level: native, good
  link         VARCHAR(255) NULL,                 -- project URL or credential URL
  start_date   DATE NULL,
  end_date     DATE NULL,
  CONSTRAINT fk_items_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE,
  KEY idx_items_type (seeker_id, type)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Group 6: Packages (needed before jobs)
-- ---------------------------------------------------------------------
CREATE TABLE packages (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name           VARCHAR(50)  NOT NULL,           -- Free, Standard, Premium
  price          DECIMAL(10,2) NOT NULL DEFAULT 0,
  duration_days  SMALLINT UNSIGNED NOT NULL,
  job_post_limit SMALLINT UNSIGNED NOT NULL,
  description    TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE subscriptions (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,              -- the employer
  package_id  INT UNSIGNED NOT NULL,
  posts_left  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  start_date  DATE NULL,
  end_date    DATE NULL,
  status      ENUM('pending','active','expired') NOT NULL DEFAULT 'pending',
  CONSTRAINT fk_sub_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
  CONSTRAINT fk_sub_package FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Group 4: Jobs
-- ---------------------------------------------------------------------
CREATE TABLE jobs (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  company_id      INT UNSIGNED NOT NULL,
  category_id     INT UNSIGNED NULL,
  city_id         INT UNSIGNED NULL,
  subscription_id INT UNSIGNED NULL,              -- package used to post this job
  title           VARCHAR(150) NOT NULL,
  slug            VARCHAR(180) NOT NULL UNIQUE,
  job_type        ENUM('full_time','part_time','contract','internship','remote') NOT NULL DEFAULT 'full_time',
  salary_min      DECIMAL(10,2) NULL,
  salary_max      DECIMAL(10,2) NULL,
  description     TEXT NULL,
  deadline        DATE NOT NULL,
  status          ENUM('pending','approved','rejected','closed') NOT NULL DEFAULT 'pending',
  is_featured     BOOLEAN NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_jobs_company  FOREIGN KEY (company_id)      REFERENCES companies(id)     ON DELETE CASCADE,
  CONSTRAINT fk_jobs_category FOREIGN KEY (category_id)     REFERENCES categories(id)    ON DELETE SET NULL,
  CONSTRAINT fk_jobs_city     FOREIGN KEY (city_id)         REFERENCES cities(id)        ON DELETE SET NULL,
  CONSTRAINT fk_jobs_sub      FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE SET NULL,
  KEY idx_jobs_search (status, deadline, is_featured)
) ENGINE=InnoDB;

CREATE TABLE job_skills (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id    INT UNSIGNED NOT NULL,
  skill_id  INT UNSIGNED NOT NULL,
  UNIQUE KEY uq_job_skill (job_id, skill_id),
  CONSTRAINT fk_js_job   FOREIGN KEY (job_id)   REFERENCES jobs(id)   ON DELETE CASCADE,
  CONSTRAINT fk_js_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Group 5: Applying
-- ---------------------------------------------------------------------
CREATE TABLE job_applications (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id      INT UNSIGNED NOT NULL,
  seeker_id   INT UNSIGNED NOT NULL,
  resume_file VARCHAR(255) NOT NULL,              -- copy of the CV sent
  status      ENUM('applied','viewed','shortlisted','interview','hired','rejected') NOT NULL DEFAULT 'applied',
  applied_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_apply_once (job_id, seeker_id),   -- a seeker applies only once
  CONSTRAINT fk_app_job    FOREIGN KEY (job_id)    REFERENCES jobs(id)    ON DELETE CASCADE,
  CONSTRAINT fk_app_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE saved_jobs (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seeker_id  INT UNSIGNED NOT NULL,
  job_id     INT UNSIGNED NOT NULL,
  UNIQUE KEY uq_saved (seeker_id, job_id),
  CONSTRAINT fk_saved_seeker FOREIGN KEY (seeker_id) REFERENCES seekers(id) ON DELETE CASCADE,
  CONSTRAINT fk_saved_job    FOREIGN KEY (job_id)    REFERENCES jobs(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Group 7: Payments and alerts
-- ---------------------------------------------------------------------
CREATE TABLE payment_methods (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(50) NOT NULL UNIQUE,        -- Cash, bKash, Nagad, Rocket, Bank Transfer, Card
  account_no  VARCHAR(50) NULL,
  status      ENUM('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE payment_transactions (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  transaction_no    VARCHAR(30) NOT NULL UNIQUE,  -- our own invoice number
  user_id           INT UNSIGNED NOT NULL,
  subscription_id   INT UNSIGNED NOT NULL,
  payment_method_id INT UNSIGNED NOT NULL,
  total_amount      DECIMAL(10,2) NOT NULL,
  tx_id             VARCHAR(50) NULL UNIQUE,      -- TrxID from bKash/Nagad; cannot repeat
  status            ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending',
  verified_by       INT UNSIGNED NULL,            -- admin who approved
  CONSTRAINT fk_pay_user     FOREIGN KEY (user_id)           REFERENCES users(id)           ON DELETE CASCADE,
  CONSTRAINT fk_pay_sub      FOREIGN KEY (subscription_id)   REFERENCES subscriptions(id)   ON DELETE CASCADE,
  CONSTRAINT fk_pay_method   FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE RESTRICT,
  CONSTRAINT fk_pay_verifier FOREIGN KEY (verified_by)       REFERENCES users(id)           ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE notifications (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  message     VARCHAR(255) NOT NULL,
  is_read     BOOLEAN NOT NULL DEFAULT FALSE,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  KEY idx_notif_unread (user_id, is_read)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Starter data
-- ---------------------------------------------------------------------
INSERT INTO payment_methods (name) VALUES
  ('Cash'), ('bKash'), ('Nagad'), ('Rocket'), ('Bank Transfer'), ('Card');

INSERT INTO packages (name, price, duration_days, job_post_limit, description) VALUES
  ('Free',     0,    30, 1,  'Post 1 job for 30 days'),
  ('Standard', 1500, 30, 5,  'Post 5 jobs for 30 days'),
  ('Premium',  4000, 60, 20, 'Post 20 jobs for 60 days, featured listing');

INSERT INTO categories (name, slug) VALUES
  ('IT / Software', 'it-software'), ('Sales', 'sales'), ('Accounts', 'accounts'), ('Design', 'design');

INSERT INTO cities (name, division) VALUES
  ('Dhaka', 'Dhaka'), ('Chattogram', 'Chattogram'), ('Sylhet', 'Sylhet'), ('Rajshahi', 'Rajshahi');

INSERT INTO skills (name) VALUES
  ('PHP'), ('MySQL'), ('Laravel'), ('JavaScript'), ('HTML'), ('CSS'), ('Excel');

INSERT INTO cv_templates (name, layout_file) VALUES
  ('Classic', 'templates/classic.php'), ('Modern', 'templates/modern.php');
