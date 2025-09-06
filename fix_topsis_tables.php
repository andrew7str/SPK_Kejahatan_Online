<?php
// Script to fix TOPSIS tables - run this directly
// Use PDO only to avoid mysqli issues

$host = 'localhost';
$dbname = 'dss_online_crime';
$username = 'root';
$password = '';

try {
    echo "Connecting to database...\n";

    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    echo "Creating TOPSIS tables...\n";

    // Create topsis_analysis_cases table
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✓ Created topsis_analysis_cases table\n";

    // Create topsis_calculations table
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✓ Created topsis_calculations table\n";

    // Insert sample data
    $pdo->exec("
        INSERT IGNORE INTO topsis_analysis_cases
        (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by)
        VALUES
        ('KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 1),
        ('KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 1),
        ('KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 1)
    ");
    echo "✓ Inserted sample data\n";

    // Verify tables exist and show record counts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM topsis_calculations");
    $calcCount = $stmt->fetch()['count'];
    echo "✓ topsis_calculations table: $calcCount records\n";

    $stmt = $pdo->query("SELECT COUNT(*) as count FROM topsis_analysis_cases");
    $caseCount = $stmt->fetch()['count'];
    echo "✓ topsis_analysis_cases table: $caseCount records\n";

    // Check records for user ID 3
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_calculations WHERE calculated_by = ?");
    $stmt->execute([3]);
    $userCalcCount = $stmt->fetch()['count'];
    echo "✓ User ID 3 calculations: $userCalcCount records\n";

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_analysis_cases WHERE created_by = ?");
    $stmt->execute([3]);
    $userCaseCount = $stmt->fetch()['count'];
    echo "✓ User ID 3 cases: $userCaseCount records\n";

    echo "\n🎉 TOPSIS tables created successfully!\n";
    echo "Now you can run TOPSIS calculations and view results.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
