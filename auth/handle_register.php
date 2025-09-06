<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if role is set (from admin panel), otherwise default to 'client'
    $role = isset($_POST['role']) ? $_POST['role'] : 'client';
    
    // Validate role
    if (!in_array($role, ['admin', 'client'])) {
        $role = 'client';
    }
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_msg = 'Semua field harus diisi!';
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
            $_SESSION['error'] = $error_msg;
            header('Location: ../admin/manage_users.php');
        } else {
            $_SESSION['error'] = $error_msg;
            header('Location: ../index.php');
        }
        exit();
    }
    
    if ($password !== $confirm_password) {
        $error_msg = 'Password dan konfirmasi password tidak sama!';
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
            $_SESSION['error'] = $error_msg;
            header('Location: ../admin/manage_users.php');
        } else {
            $_SESSION['error'] = $error_msg;
            header('Location: ../index.php');
        }
        exit();
    }
    
    if (strlen($password) < 6) {
        $error_msg = 'Password minimal 6 karakter!';
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
            $_SESSION['error'] = $error_msg;
            header('Location: ../admin/manage_users.php');
        } else {
            $_SESSION['error'] = $error_msg;
            header('Location: ../index.php');
        }
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Format email tidak valid!';
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
            $_SESSION['error'] = $error_msg;
            header('Location: ../admin/manage_users.php');
        } else {
            $_SESSION['error'] = $error_msg;
            header('Location: ../index.php');
        }
        exit();
    }
    
    try {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error_msg = 'Username sudah digunakan!';
            if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
                $_SESSION['error'] = $error_msg;
                header('Location: ../admin/manage_users.php');
            } else {
                $_SESSION['error'] = $error_msg;
                header('Location: ../index.php');
            }
            exit();
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error_msg = 'Email sudah digunakan!';
            if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
                $_SESSION['error'] = $error_msg;
                header('Location: ../admin/manage_users.php');
            } else {
                $_SESSION['error'] = $error_msg;
                header('Location: ../index.php');
            }
            exit();
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $hashed_password, $role]);
        
        // Check if request came from admin panel
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
            $_SESSION['success'] = 'User berhasil ditambahkan!';
            header('Location: ../admin/manage_users.php');
        } else {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
            header('Location: ../index.php');
        }
        exit();
        
    } catch (PDOException $e) {
        $error_msg = 'Terjadi kesalahan sistem!';
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
            $_SESSION['error'] = $error_msg;
            header('Location: ../admin/manage_users.php');
        } else {
            $_SESSION['error'] = $error_msg;
            header('Location: ../index.php');
        }
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>
