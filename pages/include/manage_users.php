<?php 
include 'header.php'; 
include 'database.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    echo "<p>Anda tidak memiliki akses ke halaman ini.</p>";
    include 'footer.php';
    exit();
}

// Ambil semua pengguna dari database
$sql = "SELECT id, username, email, role, created_at FROM users";
$result = $conn->query($sql);
$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

?>

<section>
    <h2>Manajemen Pengguna</h2>
    <p>Di halaman ini, Anda dapat melihat dan mengelola pengguna sistem.</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tidak ada pengguna ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- TODO: Add forms for adding/editing users -->

</section>

<?php 
include 'footer.php'; 
?>