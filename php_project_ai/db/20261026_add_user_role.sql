-- Existing accounts become customers by default. Promote only trusted accounts:
-- UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
-- UPDATE users SET role = 'staff' WHERE email = 'staff@example.com';
ALTER TABLE users
    ADD COLUMN role ENUM('admin', 'customer', 'staff') NOT NULL DEFAULT 'customer'
    AFTER password_hash;
