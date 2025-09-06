<?php
// Script untuk membuat user admin dengan password yang benar
require_once 'config/database.php';

// Password yang akan di-hash
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Password hash untuk 'admin123': " . $hashed_password . "\n\n";

try {
    // Cek apakah database sudah ada
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "Tabel users belum ada. Silakan import database_schema.sql terlebih dahulu.\n";
        exit;
    }
    
    // Cek apakah user admin sudah ada
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Update password admin yang sudah ada
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $stmt->execute([$hashed_password]);
        echo "Password admin berhasil diupdate!\n";
    } else {
        // Insert user admin baru
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, full_name, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@polseksaribudolok.go.id', $hashed_password, 'admin', 'Administrator', 'active']);
        echo "User admin berhasil dibuat!\n";
    }
    
    echo "Login dengan:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "\nDatabase 'dss_online_crime' belum dibuat.\n";
        echo "Silakan buat database terlebih dahulu dengan perintah:\n";
        echo "CREATE DATABASE dss_online_crime;\n";
        echo "Kemudian import file database_schema.sql\n";
    }
}
?>
