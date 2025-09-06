<?php
session_start();
require_once 'config/database.php';

echo "<h1>Debug Results Page</h1>";
echo "<h2>Database Tables Check</h2>";

// Check if tables exist
$tables = ['ahp_results', 'topsis_calculations', 'topsis_analysis_cases', 'criteria'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ $table table exists</p>";
        } else {
            echo "<p style='color: red;'>✗ $table table does not exist</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error checking $table: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>Data Check</h2>";

// Check AHP results
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ahp_results WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id'] ?? 1]);
    $result = $stmt->fetch();
    echo "<p>AHP Results for user: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error checking AHP results: " . $e->getMessage() . "</p>";
}

// Check TOPSIS calculations
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_calculations WHERE calculated_by = ?");
    $stmt->execute([$_SESSION['user_id'] ?? 1]);
    $result = $stmt->fetch();
    echo "<p>TOPSIS Calculations for user: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error checking TOPSIS calculations: " . $e->getMessage() . "</p>";
}

// Check TOPSIS analysis cases
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topsis_analysis_cases WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id'] ?? 1]);
    $result = $stmt->fetch();
    echo "<p>TOPSIS Analysis Cases for user: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error checking TOPSIS analysis cases: " . $e->getMessage() . "</p>";
}

echo "<h2>Sample Data</h2>";

// Show sample AHP results
try {
    $stmt = $pdo->prepare("SELECT * FROM ahp_results WHERE created_by = ? LIMIT 5");
    $stmt->execute([$_SESSION['user_id'] ?? 1]);
    $results = $stmt->fetchAll();
    echo "<h3>AHP Results Sample:</h3>";
    echo "<pre>" . print_r($results, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error fetching AHP results: " . $e->getMessage() . "</p>";
}

// Show sample TOPSIS calculations
try {
    $stmt = $pdo->prepare("SELECT * FROM topsis_calculations WHERE calculated_by = ? LIMIT 5");
    $stmt->execute([$_SESSION['user_id'] ?? 1]);
    $results = $stmt->fetchAll();
    echo "<h3>TOPSIS Calculations Sample:</h3>";
    echo "<pre>" . print_r($results, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error fetching TOPSIS calculations: " . $e->getMessage() . "</p>";
}
?>
