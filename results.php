<?php 
include 'header.php'; 
include 'database.php';

// Get rankings
$sql = "SELECT * FROM rankings ORDER BY score DESC";
$result = $conn->query($sql);
$rankings = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}
?>

<section>
    <h2>Hasil Peringkat Prioritas Kasus</h2>
    <?php if (count($rankings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nama Kasus</th>
                    <th>Skor TOPSIS</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach ($rankings as $ranking): ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo htmlspecialchars($ranking['alternative_name']); ?></td>
                        <td><?php echo number_format($ranking['score'], 4); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada data peringkat. Silakan input alternatif dan hitung TOPSIS terlebih dahulu di halaman TOPSIS.</p>
    <?php endif; ?>
</section>

<?php 
include 'footer.php'; 
?>