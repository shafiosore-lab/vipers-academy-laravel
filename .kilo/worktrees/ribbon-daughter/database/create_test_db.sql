-- Create test database for Laravel testing
CREATE DATABASE IF NOT EXISTS webviper_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the test database
USE webviper_test;

-- Copy structure from main database (this will be done via migrations)
-- The migrations will create all necessary tables
