<?php
session_start();
// Ambil pesan dari session jika ada
$message = '';
$message_type = '';
if (isset($_SESSION['login_message'])) {
    $message = $_SESSION['login_message'];
    $message_type = $_SESSION['login_message_type'] ?? 'error';
    unset($_SESSION['login_message']);
    unset($_SESSION['login_message_type']);
}

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DSS Online Crime</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h2>Login</h2>
            <p>Selamat datang kembali! Masuk untuk melanjutkan.</p>
            <?php if ($message): ?>
                <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
            <?php endif; ?>
            <form id="login-form" method="POST" action="handle_login.php">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
            <div class="auth-link">
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>