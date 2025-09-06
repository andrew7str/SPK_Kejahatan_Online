<?php
session_start();
// Jika pengguna tidak login, arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user_role = $_SESSION['role'];
$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DSS Online Crime</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button id="toggle-sidebar" class="toggle-btn">&#9776;</button>
            <h3 class="menu-text">Menu</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><span>&#128229;</span> <span class="menu-text">Dashboard</span></a></li>
            <?php if ($user_role == 'admin'): ?>
                <li><a href="ahp.php"><span>&#9878;</span> <span class="menu-text">AHP</span></a></li>
                <li><a href="topsis.php"><span>&#128202;</span> <span class="menu-text">TOPSIS</span></a></li>
                <li><a href="results.php"><span>&#128203;</span> <span class="menu-text">Hasil</span></a></li>
                <li><a href="manage_users.php"><span>&#128100;</span> <span class="menu-text">Kelola Pengguna</span></a></li>
            <?php elseif ($user_role == 'client'): ?>
                <li><a href="results.php"><span>&#128203;</span> <span class="menu-text">Hasil</span></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content" id="main-content">
        <header class="top-header">
            <div class="user-info">
                <span class="user-name"><?php echo $username; ?></span>
                <span class="user-role"><?php echo ucfirst($user_role); ?></span>
            </div>
            <a href="auth/logout.php" class="logout-btn" title="Logout">&#128682;</a>
        </header>
        <main>