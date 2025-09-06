-- Create TOPSIS analysis cases table
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
);

-- Insert sample data for testing
INSERT IGNORE INTO topsis_analysis_cases (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by) VALUES
('KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 1),
('KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 1),
('KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 1);
