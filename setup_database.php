<?php
// Manual database setup script
// Jalankan file ini untuk setup database secara manual

echo "=== SETUP DATABASE DSS ONLINE CRIME ===\n";
echo "Memulai inisialisasi database...\n\n";

// Include the initialization script
require_once 'config/init_database.php';

echo "\n=== SETUP SELESAI ===\n";
echo "Database berhasil disetup!\n";
echo "Anda sekarang dapat menggunakan sistem dengan:\n";
echo "- Username: admin\n";
echo "- Password: admin123\n\n";
echo "Atau:\n";
echo "- Username: officer1\n";
echo "- Password: officer123\n\n";
echo "Silakan akses halaman input kasus untuk menguji sistem.\n";
?>
