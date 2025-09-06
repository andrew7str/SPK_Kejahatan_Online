<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

// Debug: Log the request
error_log('TOPSIS Save Request: ' . $_SERVER['REQUEST_METHOD']);
if (isset($_POST['cases'])) {
    error_log('Cases data received: ' . $_POST['cases']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cases'])) {
    $cases = json_decode($_POST['cases'], true);
    if (json_last_error() === JSON_ERROR_NONE) {
        try {
            // First, ensure the table exists with proper constraints
            $pdo->exec('
                CREATE TABLE IF NOT EXISTS topsis_analysis_cases (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    case_id VARCHAR(50) NOT NULL,
                    case_name VARCHAR(200) NOT NULL,
                    kerugian BIGINT NOT NULL DEFAULT 0,
                    korban INT NOT NULL DEFAULT 1,
                    urgensi INT NOT NULL DEFAULT 1,
                    penyebaran INT NOT NULL DEFAULT 1,
                    created_by INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
                    UNIQUE KEY unique_case_per_user (case_id, created_by),
                    INDEX idx_created_by (created_by),
                    INDEX idx_case_id (case_id)
                )
            ');

            // Drop the old unique constraint if it exists
            try {
                $pdo->exec('ALTER TABLE topsis_analysis_cases DROP INDEX case_id');
            } catch (Exception $e) {
                // Ignore if constraint doesn't exist
            }

            // Start transaction
            $pdo->beginTransaction();

            // Delete existing cases for this user only
            $stmt = $pdo->prepare("DELETE FROM topsis_analysis_cases WHERE created_by = ?");
            $stmt->execute([$_SESSION['user_id']]);

            // Insert new cases - now with composite unique key (case_id, created_by)
            $stmt = $pdo->prepare("
                INSERT INTO topsis_analysis_cases
                (case_id, case_name, kerugian, korban, urgensi, penyebaran, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($cases as $case) {
                $stmt->execute([
                    $case['id'],
                    $case['name'],
                    $case['kerugian'],
                    $case['korban'],
                    $case['urgensi'],
                    $case['penyebaran'],
                    $_SESSION['user_id']
                ]);
            }

            $pdo->commit();
            echo 'success';
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo 'Database error: ' . $e->getMessage();
        }
    } else {
        http_response_code(400);
        echo 'Invalid JSON: ' . json_last_error_msg();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'load') {
        try {
            // Ensure table exists with proper constraints
            $pdo->exec('
                CREATE TABLE IF NOT EXISTS topsis_analysis_cases (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    case_id VARCHAR(50) NOT NULL,
                    case_name VARCHAR(200) NOT NULL,
                    kerugian BIGINT NOT NULL DEFAULT 0,
                    korban INT NOT NULL DEFAULT 1,
                    urgensi INT NOT NULL DEFAULT 1,
                    penyebaran INT NOT NULL DEFAULT 1,
                    created_by INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
                    UNIQUE KEY unique_case_per_user (case_id, created_by),
                    INDEX idx_created_by (created_by),
                    INDEX idx_case_id (case_id)
                )
            ');

            // Drop the old unique constraint if it exists
            try {
                $pdo->exec('ALTER TABLE topsis_analysis_cases DROP INDEX case_id');
            } catch (Exception $e) {
                // Ignore if constraint doesn't exist
            }

            $stmt = $pdo->prepare("
                SELECT case_id as id, case_name as name, kerugian, korban, urgensi, penyebaran
                FROM topsis_analysis_cases
                WHERE created_by = ?
                ORDER BY created_at ASC
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($cases);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>
