<?php
require_once 'config/database.php';

try {
    $pdo->exec('
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
        )
    ');
    echo 'Table topsis_analysis_cases created successfully';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
