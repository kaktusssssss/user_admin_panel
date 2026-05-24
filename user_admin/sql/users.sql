-- Create db
CREATE DATABASE IF NOT EXISTS `user_admin_db` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `user_admin_db`;

-- Drop table if exists
DROP TABLE IF EXISTS `users`;

-- Create table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `gender` ENUM('male', 'female', 'other') NOT NULL,
    `birth_date` DATE NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Test users:
-- Username: admin / Password: admin123
-- Username: john_doe / Password: password123
-- Username: jane_smith / Password: password123
INSERT INTO `users` (`login`, `password`, `first_name`, `last_name`, `gender`, `birth_date`) VALUES
('admin', '$2y$10$V/OioXwoah8AFgT/L.pjgOXFK9beHE0p1vMh/VeYgxw9QZ6l4JX/K', 'Admin', 'User', 'other', '1990-01-01'),
('john_doe', '$2y$10$3D.3m6IWl3lTGtCHPB.C7.uBP.ir3pJIuQXWz24JmV9AbUBazIwQW', 'John', 'Doe', 'male', '1995-05-15'),
('jane_smith', '$2y$10$kpWjvJt868U4/d/UA0r0.e07EsIX6RAXJxPRqery4pNWD/UENK8QW', 'Jane', 'Smith', 'female', '1992-08-22');

