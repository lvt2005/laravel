-- =============================================================================
-- MySQL Initialization Script cho Laravel Medical Clinic
-- =============================================================================
-- File này sẽ được thực thi tự động khi MySQL container khởi động lần đầu

-- Set character encoding
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Tạo database nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS laravel_clinic
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Sử dụng database
USE laravel_clinic;

-- Hiển thị thông báo
SELECT 'Database laravel_clinic initialized successfully!' AS Status;

