-- Database schema for DSS Online Crime System
-- Sistem Pendukung Keputusan Prioritas Penanganan Kejahatan Online
-- Menggunakan metode AHP dan TOPSIS

CREATE DATABASE IF NOT EXISTS dss_online_crime;
USE dss_online_crime;

-- Users table - Tabel pengguna sistem
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client', 'officer') DEFAULT 'client',
    full_name VARCHAR(100),
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Criteria table - Tabel kriteria penilaian
CREATE TABLE criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    type ENUM('benefit', 'cost') DEFAULT 'benefit',
    weight DECIMAL(8,6) DEFAULT 0.000000,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cases table - Tabel kasus kejahatan online
CREATE TABLE cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(50) UNIQUE NOT NULL,
    case_name VARCHAR(200) NOT NULL,
    case_type ENUM('phishing', 'hacking', 'fraud', 'cyberbullying', 'identity_theft', 'online_scam', 'malware', 'other') NOT NULL,
    description TEXT,
    reporter_name VARCHAR(100),
    reporter_contact VARCHAR(100),
    incident_date DATE,
    report_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'investigating', 'resolved', 'closed') DEFAULT 'pending',
    priority_level ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    assigned_officer VARCHAR(100),
    estimated_loss DECIMAL(15,2) DEFAULT 0.00,
    victim_count INT DEFAULT 1,
    urgency_level INT DEFAULT 1 CHECK (urgency_level BETWEEN 1 AND 5),
    spread_potential INT DEFAULT 1 CHECK (spread_potential BETWEEN 1 AND 5),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON SET NULL
);

-- AHP pairwise comparisons - Perbandingan berpasangan AHP
CREATE TABLE ahp_comparisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    criteria1_id INT NOT NULL,
    criteria2_id INT NOT NULL,
    comparison_value DECIMAL(8,6) NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criteria1_id) REFERENCES criteria(id) ON DELETE CASCADE,
    FOREIGN KEY (criteria2_id) REFERENCES criteria(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON SET NULL,
    UNIQUE KEY unique_comparison (session_id, criteria1_id, criteria2_id)
);

-- AHP results - Hasil perhitungan AHP
CREATE TABLE ahp_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    criteria_id INT NOT NULL,
    weight DECIMAL(8,6) NOT NULL,
    consistency_ratio DECIMAL(8,6),
    is_consistent BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criteria_id) REFERENCES criteria(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON SET NULL
);

-- Case evaluations - Penilaian kasus berdasarkan kriteria
CREATE TABLE case_evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    criteria_id INT NOT NULL,
    score DECIMAL(8,4) NOT NULL,
    normalized_score DECIMAL(8,6),
    weighted_score DECIMAL(8,6),
    evaluator_id INT,
    evaluation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (criteria_id) REFERENCES criteria(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluator_id) REFERENCES users(id) ON SET NULL,
    UNIQUE KEY unique_evaluation (case_id, criteria_id)
);

-- TOPSIS analysis cases - Kasus untuk analisis TOPSIS
CREATE TABLE topsis_analysis_cases (
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

-- TOPSIS calculations - Perhitungan TOPSIS
CREATE TABLE topsis_calculations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    case_id INT NOT NULL,
    positive_distance DECIMAL(10,8),
    negative_distance DECIMAL(10,8),
    closeness_coefficient DECIMAL(8,6),
    rank_position INT,
    calculated_by INT,
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (calculated_by) REFERENCES users(id) ON SET NULL
);

-- Decision sessions - Sesi pengambilan keputusan
CREATE TABLE decision_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) UNIQUE NOT NULL,
    session_name VARCHAR(200) NOT NULL,
    description TEXT,
    method ENUM('ahp', 'topsis', 'ahp_topsis') DEFAULT 'ahp_topsis',
    status ENUM('draft', 'calculating', 'completed', 'archived') DEFAULT 'draft',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON SET NULL
);

-- System logs - Log sistem
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON SET NULL
);

-- Settings table - Pengaturan sistem
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON SET NULL
);

-- Insert default admin user
-- Password: admin123 (hashed)
INSERT INTO users (username, email, password, role, full_name, status) VALUES 
('admin', 'admin@polseksaribudolok.go.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrator', 'active'),
('officer1', 'officer1@polseksaribudolok.go.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'officer', 'Petugas Reskrim', 'active');

-- Sub criteria table - Tabel sub kriteria
CREATE TABLE sub_criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    criteria_id INT NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    score_range VARCHAR(50) DEFAULT '1-5',
    weight DECIMAL(8,6) DEFAULT 0.000000,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (criteria_id) REFERENCES criteria(id) ON DELETE CASCADE
);

-- Alternatives table - Tabel alternatif (kasus yang akan diprioritaskan)
CREATE TABLE alternatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    alternative_name VARCHAR(200) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE
);

-- Insert default criteria sesuai dengan skripsi
INSERT INTO criteria (code, name, description, type, weight) VALUES 
('C1', 'Tingkat Kerugian', 'Besarnya kerugian materiil (uang/barang) yang dialami korban akibat kasus kejahatan online', 'benefit', 0.5748),
('C2', 'Tingkat Dampak', 'Sejauh mana kasus berdampak pada masyarakat/instansi (keresahan publik, reputasi)', 'benefit', 0.2352),
('C3', 'Urgensi Penanganan', 'Tingkat kepentingan atau seberapa cepat kasus harus segera ditangani', 'benefit', 0.1262),
('C4', 'Ketersediaan Sumber Daya', 'Kesiapan personel, teknologi, dan fasilitas untuk menangani kasus', 'benefit', 0.0638);

-- Insert sub criteria untuk setiap kriteria utama
INSERT INTO sub_criteria (criteria_id, code, name, description, score_range) VALUES 
-- Sub kriteria untuk C1 (Tingkat Kerugian)
(1, 'C1.1', 'Kerugian Sangat Rendah', 'Kerugian < Rp 10.000.000', '1'),
(1, 'C1.2', 'Kerugian Rendah', 'Kerugian Rp 10.000.000 - Rp 25.000.000', '2'),
(1, 'C1.3', 'Kerugian Sedang', 'Kerugian Rp 25.000.000 - Rp 50.000.000', '3'),
(1, 'C1.4', 'Kerugian Tinggi', 'Kerugian Rp 50.000.000 - Rp 100.000.000', '4'),
(1, 'C1.5', 'Kerugian Sangat Tinggi', 'Kerugian > Rp 100.000.000', '5'),

-- Sub kriteria untuk C2 (Tingkat Dampak)
(2, 'C2.1', 'Dampak Sangat Rendah', 'Hanya mempengaruhi korban langsung', '1'),
(2, 'C2.2', 'Dampak Rendah', 'Mempengaruhi keluarga korban', '2'),
(2, 'C2.3', 'Dampak Sedang', 'Mempengaruhi komunitas kecil', '3'),
(2, 'C2.4', 'Dampak Tinggi', 'Mempengaruhi masyarakat luas', '4'),
(2, 'C2.5', 'Dampak Sangat Tinggi', 'Mempengaruhi stabilitas sosial/ekonomi', '5'),

-- Sub kriteria untuk C3 (Urgensi Penanganan)
(3, 'C3.1', 'Urgensi Sangat Rendah', 'Dapat ditangani dalam 1 bulan', '1'),
(3, 'C3.2', 'Urgensi Rendah', 'Perlu ditangani dalam 2 minggu', '2'),
(3, 'C3.3', 'Urgensi Sedang', 'Perlu ditangani dalam 1 minggu', '3'),
(3, 'C3.4', 'Urgensi Tinggi', 'Perlu ditangani dalam 3 hari', '4'),
(3, 'C3.5', 'Urgensi Sangat Tinggi', 'Perlu ditangani segera (< 24 jam)', '5'),

-- Sub kriteria untuk C4 (Ketersediaan Sumber Daya)
(4, 'C4.1', 'Sumber Daya Sangat Terbatas', 'Memerlukan bantuan eksternal', '1'),
(4, 'C4.2', 'Sumber Daya Terbatas', 'Memerlukan alokasi khusus', '2'),
(4, 'C4.3', 'Sumber Daya Cukup', 'Dapat ditangani dengan tim standar', '3'),
(4, 'C4.4', 'Sumber Daya Baik', 'Tim dan peralatan tersedia', '4'),
(4, 'C4.5', 'Sumber Daya Sangat Baik', 'Tim ahli dan peralatan lengkap tersedia', '5');

-- Insert sample cases untuk testing
INSERT INTO cases (case_number, case_name, case_type, description, reporter_name, incident_date, estimated_loss, victim_count, urgency_level, spread_potential) VALUES 
('KASUS001/2025', 'Penipuan Online Investasi Bodong', 'online_scam', 'Kasus penipuan investasi online dengan kerugian mencapai 49 juta rupiah', 'Budi Santoso', '2025-01-15', 49944304.00, 3, 4, 3),
('KASUS002/2025', 'Phishing Banking', 'phishing', 'Kasus phishing yang menargetkan nasabah bank dengan kerugian 55 juta rupiah', 'Siti Aminah', '2025-01-16', 55000000.00, 4, 5, 3),
('KASUS003/2025', 'Hacking Website Pemerintah', 'hacking', 'Peretasan website pemerintah daerah dengan kerugian 63 juta rupiah', 'Dinas Kominfo', '2025-01-17', 63000000.00, 3, 3, 2);

-- Insert sample evaluations
INSERT INTO case_evaluations (case_id, criteria_id, score) VALUES
-- Kasus 1
(1, 1, 4.5), -- Tingkat Kerugian
(1, 2, 3.0), -- Tingkat Dampak
(1, 3, 4.0), -- Urgensi
(1, 4, 3.0), -- Sumber Daya
-- Kasus 2
(2, 1, 5.0),
(2, 2, 4.0),
(2, 3, 5.0),
(2, 4, 3.0),
-- Kasus 3
(3, 1, 4.8),
(3, 2, 3.0),
(3, 3, 3.0),
(3, 4, 2.0);

-- Insert sample TOPSIS analysis cases
INSERT INTO topsis_analysis_cases (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by) VALUES
('KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 1),
('KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 1),
('KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 1);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description, is_public) VALUES 
('system_name', 'Sistem Pendukung Keputusan Prioritas Penanganan Kejahatan Online', 'string', 'Nama sistem aplikasi', TRUE),
('organization_name', 'Polsek Saribudolok', 'string', 'Nama organisasi', TRUE),
('max_consistency_ratio', '0.1', 'number', 'Batas maksimal rasio konsistensi AHP', FALSE),
('default_session_timeout', '3600', 'number', 'Timeout sesi dalam detik', FALSE),
('enable_logging', 'true', 'boolean', 'Aktifkan logging sistem', FALSE);

-- Create indexes for better performance
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_cases_status ON cases(status);
CREATE INDEX idx_cases_type ON cases(case_type);
CREATE INDEX idx_cases_date ON cases(report_date);
CREATE INDEX idx_case_evaluations_case ON case_evaluations(case_id);
CREATE INDEX idx_ahp_session ON ahp_comparisons(session_id);
CREATE INDEX idx_topsis_session ON topsis_calculations(session_id);
CREATE INDEX idx_topsis_analysis_created_by ON topsis_analysis_cases(created_by);
CREATE INDEX idx_topsis_analysis_case_id ON topsis_analysis_cases(case_id);
CREATE INDEX idx_logs_user ON system_logs(user_id);
CREATE INDEX idx_logs_date ON system_logs(created_at);

-- Create views for easier data access
CREATE VIEW v_case_summary AS
SELECT 
    c.id,
    c.case_number,
    c.case_name,
    c.case_type,
    c.status,
    c.priority_level,
    c.estimated_loss,
    c.victim_count,
    c.report_date,
    u.full_name as created_by_name,
    COUNT(ce.id) as evaluation_count
FROM cases c
LEFT JOIN users u ON c.created_by = u.id
LEFT JOIN case_evaluations ce ON c.id = ce.case_id
GROUP BY c.id;

CREATE VIEW v_criteria_weights AS
SELECT 
    c.id,
    c.code,
    c.name,
    c.description,
    c.type,
    c.weight,
    c.is_active
FROM criteria c
WHERE c.is_active = TRUE
ORDER BY c.weight DESC;
