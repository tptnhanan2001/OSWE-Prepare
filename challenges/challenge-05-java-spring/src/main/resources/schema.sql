CREATE DATABASE IF NOT EXISTS challenge_db;
USE challenge_db;

CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('user', 'user123', 'user');

CREATE TABLE employees (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(50),
    email VARCHAR(100)
);

INSERT INTO employees (name, department, email) VALUES
('John Doe', 'IT', 'john@example.com'),
('Jane Smith', 'HR', 'jane@example.com');

