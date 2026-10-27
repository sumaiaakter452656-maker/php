
CREATE DATABASE IF NOT EXISTS cleaning_service
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cleaning_service;

-- 1. Users: administrators, customers and staff
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer', 'staff')
        NOT NULL DEFAULT 'customer',
    address TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Cleaning services
CREATE TABLE services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Customer service requests
CREATE TABLE service_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    request_number VARCHAR(30) NOT NULL UNIQUE,

    customer_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    assigned_staff_id INT UNSIGNED NULL,

    service_address TEXT NOT NULL,
    preferred_date DATE NOT NULL,
    preferred_time TIME NOT NULL,
    description TEXT NULL,

    status ENUM(
        'pending',
        'approved',
        'assigned',
        'in_progress',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    final_amount DECIMAL(10,2) NULL,

    completed_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
        
    INDEX idx_request_customer (customer_id),
    INDEX idx_request_staff (assigned_staff_id),
    INDEX idx_request_status_date (status, preferred_date)
) ENGINE=InnoDB;

-- 4. Bills
CREATE TABLE bills (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bill_number VARCHAR(30) NOT NULL UNIQUE,
    request_id INT UNSIGNED NOT NULL UNIQUE,
    generated_by INT UNSIGNED NULL,

    amount DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    tax DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,

    payment_status ENUM(
        'unpaid',
        'partially_paid',
        'paid',
        'cancelled'
    ) NOT NULL DEFAULT 'unpaid',

    payment_method ENUM(
        'cash',
        'bank_transfer',
        'mobile_banking',
        'card',
        'other'
    ) NULL,

    issued_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    paid_at DATETIME NULL,
    notes TEXT NULL,

    INDEX idx_bill_payment_status (payment_status)
) ENGINE=InnoDB;

-- 5. Customer reviews
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    request_id INT UNSIGNED NOT NULL UNIQUE,
    customer_id INT UNSIGNED NOT NULL,

    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_review_customer (customer_id)
) ENGINE=InnoDB;
