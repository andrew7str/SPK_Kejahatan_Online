<?php
require_once 'config/database.php';

echo "<h1>Database Test</h1>";

// Test database connection
try {
    $stmt = $pdo->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

// Check if topsis_analysis_cases table exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'topsis_analysis_cases'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ topsis_analysis_cases table exists</p>";
    } else {
        echo "<p style='color: red;'>✗ topsis_analysis_cases table does not exist</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error checking table: " . $e->getMessage() . "</p>";
}

// Check criteria table
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM criteria");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ Criteria table has " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error checking criteria: " . $e->getMessage() . "</p>";
}

// Check current user session
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<p style='color: green;'>✓ User session active (ID: " . $_SESSION['user_id'] . ")</p>";
} else {
    echo "<p style='color: red;'>✗ No active user session</p>";
}
?>
