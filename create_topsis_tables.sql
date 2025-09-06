-- SQL Script to create TOPSIS tables for DSS Online Crime System
-- Run this script in phpMyAdmin to create the necessary tables

-- Create topsis_analysis_cases table
CREATE TABLE IF NOT EXISTS topsis_analysis_cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_id VARCHAR(50) UNIQUE NOT NULL,
    case_name VARCHAR(200) NOT NULL,
    kerugian BIGINT NOT NULL DEFAULT 0,
    korban INT NOT NULL DEFAULT 1,
    urgensi INT NOT NULL DEFAULT 1,
    penyebaran INT NOT NULL DEFAULT 1,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_created_by (created_by),
    INDEX idx_case_id (case_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create topsis_calculations table
CREATE TABLE IF NOT EXISTS topsis_calculations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    case_id VARCHAR(50) NOT NULL,
    positive_distance DECIMAL(10,8),
    negative_distance DECIMAL(10,8),
    closeness_coefficient DECIMAL(8,6),
    rank_position INT,
    calculated_by INT,
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (calculated_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_session (session_id),
    INDEX idx_calculated_by (calculated_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing (optional)
INSERT IGNORE INTO topsis_analysis_cases (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by) VALUES
('KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 1),
('KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 1),
('KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 1);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_topsis_analysis_created_by ON topsis_analysis_cases(created_by);
CREATE INDEX IF NOT EXISTS idx_topsis_analysis_case_id ON topsis_analysis_cases(case_id);
CREATE INDEX IF NOT EXISTS idx_topsis_session ON topsis_calculations(session_id);
CREATE INDEX IF NOT EXISTS idx_topsis_calculated_by ON topsis_calculations(calculated_by);

COMMIT;
