-- Database schema for DSS Online Crime system

CREATE DATABASE IF NOT EXISTS dss_online_crime;
USE dss_online_crime;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(50) NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criteria table
CREATE TABLE IF NOT EXISTS criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Pairwise comparisons for AHP
CREATE TABLE IF NOT EXISTS pairwise_comparisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    criteria1_id INT,
    criteria2_id INT,
    value DECIMAL(5,2),
    FOREIGN KEY (criteria1_id) REFERENCES criteria(id),
    FOREIGN KEY (criteria2_id) REFERENCES criteria(id),
    UNIQUE KEY unique_comparison (criteria1_id, criteria2_id)
);

-- Criteria weights
CREATE TABLE IF NOT EXISTS criteria_weights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    criteria_id INT,
    weight DECIMAL(5,4),
    FOREIGN KEY (criteria_id) REFERENCES criteria(id),
    UNIQUE KEY unique_weight (criteria_id)
);

-- Alternatives table
CREATE TABLE IF NOT EXISTS alternatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL DEFAULT 'Lainnya',
    status ENUM('Baru', 'Dalam Proses', 'Selesai') NOT NULL DEFAULT 'Baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alternative scores
CREATE TABLE IF NOT EXISTS alternative_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alternative_id INT,
    criteria_id INT,
    score DECIMAL(5,2),
    FOREIGN KEY (alternative_id) REFERENCES alternatives(id),
    FOREIGN KEY (criteria_id) REFERENCES criteria(id),
    UNIQUE KEY unique_score (alternative_id, criteria_id)
);

-- Rankings table
CREATE TABLE IF NOT EXISTS rankings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alternative_name VARCHAR(255),
    score DECIMAL(10,4),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_ranking (alternative_name)
);

-- Insert default criteria
INSERT INTO criteria (name, description) VALUES
('Tingkat Kerugian', 'Tingkat kerugian finansial yang disebabkan oleh kejahatan online'),
('Jumlah Korban', 'Jumlah korban yang terdampak oleh kejahatan online'),
('Urgensi', 'Tingkat urgensi penanganan kasus'),
('Potensi Penyebaran', 'Potensi penyebaran kejahatan ke korban lain'),
('Kompleksitas', 'Tingkat kesulitan teknis dan investigasi kasus');

-- Insert default user (password: admin123)
INSERT INTO users (username, password, email, role) VALUES
('admin', 'tidakada', 'admin@example.com', 'admin');

-- Insert default client user (password: anggota123)
INSERT INTO users (username, password, email, role) VALUES
('anggota', 'anggota123', 'anggota@example.com', 'client');
