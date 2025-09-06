<?php
// Database configuration
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
    
    // Check if tables exist, if not initialize database
    $stmt = $pdo->query("SHOW TABLES LIKE 'cases'");
    if ($stmt->rowCount() == 0) {
        // Tables don't exist, run initialization
        require_once __DIR__ . '/init_database.php';
    }
    
} catch(PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database connection error: " . $e->getMessage());
    die("Koneksi database gagal. Pastikan MySQL berjalan dan konfigurasi database benar.");
}

// Legacy mysqli connection for backward compatibility
try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
} catch (Exception $e) {
    error_log("MySQLi connection error: " . $e->getMessage());
    $conn = null; // Set to null if connection fails
}
?>
