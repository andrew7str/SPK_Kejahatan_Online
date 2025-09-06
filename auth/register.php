<?php
session_start();
// Ambil pesan dari session jika ada
$message = '';
$message_type = '';
if (isset($_SESSION['register_message'])) {
    $message = $_SESSION['register_message'];
    $message_type = $_SESSION['register_message_type'] ?? 'error';
    unset($_SESSION['register_message']);
    unset($_SESSION['register_message_type']);
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
    <title>Register - Sistem Pendukung Keputusan</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h2>Buat Akun Baru</h2>
            <p>Daftar untuk mendapatkan akses sebagai client.</p>
            <?php if ($message): ?>
                <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="POST" action="handle_register.php">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Register</button>
            </form>
            <div class="auth-link">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>