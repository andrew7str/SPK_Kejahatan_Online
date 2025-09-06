<?php
// Database initialization script
// Script untuk inisialisasi database lengkap

$host = 'localhost';
$dbname = 'dss_online_crime';
$username = 'root';
$password = '';

try {
    // Create database if not exists
    $pdo_temp = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    echo "Database connection successful...\n";
    
    // Drop existing tables if they exist (untuk reset ulang)
    // Urutan drop harus memperhatikan foreign key constraints
    $tables = ['system_logs', 'topsis_calculations', 'case_evaluations', 'ahp_results', 'ahp_comparisons', 
               'alternatives', 'sub_criteria', 'decision_sessions', 'cases', 'criteria', 'settings', 'users'];
    
    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    foreach ($tables as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS $table");
            echo "Dropped table: $table\n";
        } catch (Exception $e) {
            echo "Warning dropping table $table: " . $e->getMessage() . "\n";
        }
    }
    
    // Drop views if they exist
    try {
        $pdo->exec("DROP VIEW IF EXISTS v_case_summary");
        $pdo->exec("DROP VIEW IF EXISTS v_criteria_weights");
        echo "Dropped existing views\n";
    } catch (Exception $e) {
        // Continue if views don't exist
    }
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Create users table
    $pdo->exec("
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
        )
    ");
    echo "Created table: users\n";
    
    // Create criteria table
    $pdo->exec("
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
        )
    ");
    echo "Created table: criteria\n";
    
    // Create cases table - TABEL UTAMA YANG DIBUTUHKAN
    $pdo->exec("
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
            created_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created table: cases\n";
    
    // Create sub_criteria table
    $pdo->exec("
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
        )
    ");
    echo "Created table: sub_criteria\n";
    
    // Create alternatives table
    $pdo->exec("
        CREATE TABLE alternatives (
            id INT AUTO_INCREMENT PRIMARY KEY,
            case_id INT NOT NULL,
            alternative_name VARCHAR(200) NOT NULL,
            description TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE
        )
    ");
    echo "Created table: alternatives\n";
    
    // Create ahp_comparisons table
    $pdo->exec("
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
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
            UNIQUE KEY unique_comparison (session_id, criteria1_id, criteria2_id)
        )
    ");
    echo "Created table: ahp_comparisons\n";
    
    // Create ahp_results table
    $pdo->exec("
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
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created table: ahp_results\n";
    
    // Create case_evaluations table
    $pdo->exec("
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
            FOREIGN KEY (evaluator_id) REFERENCES users(id) ON DELETE SET NULL,
            UNIQUE KEY unique_evaluation (case_id, criteria_id)
        )
    ");
    echo "Created table: case_evaluations\n";
    
    // Create topsis_calculations table
    $pdo->exec("
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
            FOREIGN KEY (calculated_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created table: topsis_calculations\n";
    
    // Create decision_sessions table
    $pdo->exec("
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
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created table: decision_sessions\n";
    
    // Create system_logs table
    $pdo->exec("
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
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created table: system_logs\n";
    
    // Create settings table
    $pdo->exec("
        CREATE TABLE settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
            description TEXT,
            is_public BOOLEAN DEFAULT FALSE,
            updated_by INT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Created table: settings\n";
    
    // Insert default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $officer_password = password_hash('officer123', PASSWORD_DEFAULT);
    
    $pdo->exec("
        INSERT INTO users (username, email, password, role, full_name, status) VALUES 
        ('admin', 'admin@polseksaribudolok.go.id', '$admin_password', 'admin', 'Administrator', 'active'),
        ('officer1', 'officer1@polseksaribudolok.go.id', '$officer_password', 'officer', 'Petugas Reskrim', 'active')
    ");
    echo "Inserted default users\n";
    
    // Insert default criteria
    $pdo->exec("
        INSERT INTO criteria (code, name, description, type, weight) VALUES 
        ('C1', 'Tingkat Kerugian', 'Besarnya kerugian materiil (uang/barang) yang dialami korban akibat kasus kejahatan online', 'benefit', 0.5748),
        ('C2', 'Tingkat Dampak', 'Sejauh mana kasus berdampak pada masyarakat/instansi (keresahan publik, reputasi)', 'benefit', 0.2352),
        ('C3', 'Urgensi Penanganan', 'Tingkat kepentingan atau seberapa cepat kasus harus segera ditangani', 'benefit', 0.1262),
        ('C4', 'Ketersediaan Sumber Daya', 'Kesiapan personel, teknologi, dan fasilitas untuk menangani kasus', 'benefit', 0.0638)
    ");
    echo "Inserted default criteria\n";
    
    // Insert sub criteria
    $pdo->exec("
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
        (4, 'C4.5', 'Sumber Daya Sangat Baik', 'Tim ahli dan peralatan lengkap tersedia', '5')
    ");
    echo "Inserted sub criteria\n";
    
    // Insert default settings
    $pdo->exec("
        INSERT INTO settings (setting_key, setting_value, setting_type, description, is_public) VALUES 
        ('system_name', 'Sistem Pendukung Keputusan Prioritas Penanganan Kejahatan Online', 'string', 'Nama sistem aplikasi', 1),
        ('organization_name', 'Polsek Saribudolok', 'string', 'Nama organisasi', 1),
        ('max_consistency_ratio', '0.1', 'number', 'Batas maksimal rasio konsistensi AHP', 0),
        ('default_session_timeout', '3600', 'number', 'Timeout sesi dalam detik', 0),
        ('enable_logging', 'true', 'boolean', 'Aktifkan logging sistem', 0)
    ");
    echo "Inserted default settings\n";
    
    // Create indexes for better performance
    $pdo->exec("CREATE INDEX idx_users_username ON users(username)");
    $pdo->exec("CREATE INDEX idx_users_email ON users(email)");
    $pdo->exec("CREATE INDEX idx_users_role ON users(role)");
    $pdo->exec("CREATE INDEX idx_cases_status ON cases(status)");
    $pdo->exec("CREATE INDEX idx_cases_type ON cases(case_type)");
    $pdo->exec("CREATE INDEX idx_cases_date ON cases(report_date)");
    $pdo->exec("CREATE INDEX idx_case_evaluations_case ON case_evaluations(case_id)");
    $pdo->exec("CREATE INDEX idx_ahp_session ON ahp_comparisons(session_id)");
    $pdo->exec("CREATE INDEX idx_topsis_session ON topsis_calculations(session_id)");
    $pdo->exec("CREATE INDEX idx_logs_user ON system_logs(user_id)");
    $pdo->exec("CREATE INDEX idx_logs_date ON system_logs(created_at)");
    echo "Created indexes\n";
    
    // Create views for easier data access
    $pdo->exec("
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
        GROUP BY c.id
    ");
    echo "Created view: v_case_summary\n";
    
    $pdo->exec("
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
        ORDER BY c.weight DESC
    ");
    echo "Created view: v_criteria_weights\n";
    
    echo "\n=== DATABASE INITIALIZATION COMPLETED SUCCESSFULLY ===\n";
    echo "Database: $dbname\n";
    echo "All tables created and populated with default data.\n";
    echo "Default admin user: admin / admin123\n";
    echo "Default officer user: officer1 / officer123\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
