<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$page_title = 'Hasil Analisis - Prioritas Penanganan Kasus';
require_once '../config/database.php';

// Ambil hasil AHP
$ahpResults = [];
$ahpAvailable = false;
try {
    $stmt = $pdo->prepare("
        SELECT criteria_name, weight, consistency_ratio 
        FROM ahp_results 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 4
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $ahpData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($ahpData) >= 4) {
        $ahpAvailable = true;
        $consistencyRatio = $ahpData[0]['consistency_ratio'];
        foreach ($ahpData as $row) {
            $ahpResults[$row['criteria_name']] = $row['weight'];
        }
    }
} catch (Exception $e) {
    $ahpAvailable = false;
}

// Ambil hasil TOPSIS
$topsisResults = [];
$topsisAvailable = false;
$totalCases = 0;
$priorityCount = ['high' => 0, 'medium' => 0, 'low' => 0];
$totalLoss = 0;
$totalVictims = 0;
$avgUrgency = 0;
$avgSpread = 0;

try {
    $stmt = $pdo->prepare("
        SELECT * FROM topsis_results 
        WHERE user_id = ? 
        ORDER BY ranking ASC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $topsisResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($topsisResults) > 0) {
        $topsisAvailable = true;
        $totalCases = count($topsisResults);
        
        // Hitung statistik
        foreach ($topsisResults as $result) {
            $totalLoss += $result['kerugian'];
            $totalVictims += $result['korban'];
            $avgUrgency += $result['urgensi'];
            $avgSpread += $result['penyebaran'];
            
            // Kategorikan prioritas berdasarkan ranking
            if ($result['ranking'] == 1) {
                $priorityCount['high']++;
            } elseif ($result['ranking'] == 2) {
                $priorityCount['medium']++;
            } else {
                $priorityCount['low']++;
            }
        }
        
        $avgUrgency = $totalCases > 0 ? $avgUrgency / $totalCases : 0;
        $avgSpread = $totalCases > 0 ? $avgSpread / $totalCases : 0;
    }
} catch (Exception $e) {
    $topsisAvailable = false;
}

include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Include Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-trophy me-2 text-warning"></i>
                    Hasil Analisis Prioritas
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="printResults()">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>

            <?php if (!$topsisAvailable): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Hasil Analisis Belum Tersedia</h5>
                        <p>Untuk melihat hasil analisis, Anda harus melakukan perhitungan AHP dan TOPSIS terlebih dahulu.</p>
                        <div class="btn-group">
                            <a href="ahp.php" class="btn btn-primary">
                                <i class="fas fa-chart-line me-2"></i>Lakukan AHP
                            </a>
                            <a href="topsis.php" class="btn btn-success">
                                <i class="fas fa-calculator me-2"></i>Lakukan TOPSIS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Kasus</h6>
                                    <h3 class="mb-0"><?php echo $totalCases; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-folder-open fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Prioritas Tinggi</h6>
                                    <h3 class="mb-0"><?php echo $priorityCount['high']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Prioritas Sedang</h6>
                                    <h3 class="mb-0"><?php echo $priorityCount['medium']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Prioritas Rendah</h6>
                                    <h3 class="mb-0"><?php echo $priorityCount['low']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Results -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm results-container">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Ranking Prioritas Penanganan Kasus Kejahatan Online
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <span class="badge bg-warning ms-2">Admin View</span>
                                <?php else: ?>
                                    <span class="badge bg-info ms-2">Client View</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover results-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="10%">Ranking</th>
                                            <th width="15%">ID Kasus</th>
                                            <th width="25%">Nama Kasus</th>
                                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                                <th width="15%">Skor AHP</th>
                                                <th width="15%">Skor TOPSIS</th>
                                                <th width="20%">Status Prioritas</th>
                                            <?php else: ?>
                                                <th width="20%">Skor TOPSIS</th>
                                                <th width="30%">Status Prioritas</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($topsisResults as $result): 
                                            $priorityClass = '';
                                            $priorityText = '';
                                            $priorityIcon = '';
                                            
                                            if ($result['ranking'] == 1) {
                                                $priorityClass = 'table-danger';
                                                $priorityText = 'SANGAT TINGGI';
                                                $priorityIcon = 'fas fa-exclamation-triangle';
                                                $badgeClass = 'bg-danger';
                                                $rankIcon = 'fas fa-trophy';
                                            } elseif ($result['ranking'] == 2) {
                                                $priorityClass = 'table-warning';
                                                $priorityText = 'TINGGI';
                                                $priorityIcon = 'fas fa-clock';
                                                $badgeClass = 'bg-warning';
                                                $rankIcon = 'fas fa-medal';
                                            } else {
                                                $priorityClass = 'table-info';
                                                $priorityText = 'SEDANG';
                                                $priorityIcon = 'fas fa-check-circle';
                                                $badgeClass = 'bg-info';
                                                $rankIcon = 'fas fa-award';
                                            }
                                        ?>
                                        <tr class="<?php echo $priorityClass; ?>">
                                            <td>
                                                <span class="badge <?php echo $badgeClass; ?> fs-6">
                                                    <i class="<?php echo $rankIcon; ?> me-1"></i><?php echo $result['ranking']; ?>
                                                </span>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($result['alternative_id']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($result['alternative_name']); ?></td>
                                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                                <td><span class="badge bg-primary">N/A</span></td>
                                                <td><span class="badge bg-success"><?php echo number_format($result['closeness_coefficient'], 4); ?></span></td>
                                                <td>
                                                    <span class="badge <?php echo $badgeClass; ?>">
                                                        <i class="<?php echo $priorityIcon; ?> me-1"></i>
                                                        <?php echo $priorityText; ?>
                                                    </span>
                                                </td>
                                            <?php else: ?>
                                                <td><span class="badge bg-success"><?php echo number_format($result['closeness_coefficient'], 4); ?></span></td>
                                                <td>
                                                    <span class="badge <?php echo $badgeClass; ?>">
                                                        <i class="<?php echo $priorityIcon; ?> me-1"></i>
                                                        <?php echo $priorityText; ?>
                                                    </span>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analysis -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Distribusi Prioritas
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="priorityDistributionChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Perbandingan Skor
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="scoreComparisonChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Analysis -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-analytics me-2"></i>
                                Analisis Detail
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <span class="badge bg-light text-dark ms-2">Lengkap</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark ms-2">Ringkas</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <!-- Admin View - Detailed Analysis -->
                                    <div class="col-md-4">
                                        <h6 class="text-primary">Bobot Kriteria AHP:</h6>
                                        <?php if ($ahpAvailable): ?>
                                        <ul class="list-group list-group-flush">
                                            <?php 
                                            $criteriaOrder = ['Tingkat Kerugian', 'Jumlah Korban', 'Urgensi', 'Potensi Penyebaran'];
                                            foreach ($criteriaOrder as $criteria): 
                                                $weight = isset($ahpResults[$criteria]) ? $ahpResults[$criteria] : 0;
                                                $percentage = number_format($weight * 100, 2);
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span><?php echo $criteria; ?></span>
                                                <span class="badge bg-primary"><?php echo $percentage; ?>%</span>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php else: ?>
                                        <div class="alert alert-info">
                                            <small>Bobot kriteria belum tersedia. Lakukan perhitungan AHP terlebih dahulu.</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-success">Consistency Ratio:</h6>
                                        <?php if ($ahpAvailable): ?>
                                        <div class="alert <?php echo ($consistencyRatio <= 0.10) ? 'alert-success' : 'alert-danger'; ?>">
                                            <strong>CR = <?php echo number_format($consistencyRatio, 4); ?></strong>
                                            <br>
                                            <small><?php echo ($consistencyRatio <= 0.10) ? '✓ Konsisten (CR ≤ 0.10)' : '✗ Tidak Konsisten (CR > 0.10)'; ?></small>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-info">
                                            <small>Consistency ratio belum tersedia.</small>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <h6 class="text-info mt-3">Rekomendasi Admin:</h6>
                                        <?php if ($topsisAvailable && count($topsisResults) > 0): ?>
                                        <div class="alert alert-warning">
                                            <small>
                                                <strong>Prioritas Utama:</strong> <?php echo htmlspecialchars($topsisResults[0]['alternative_name']); ?><br>
                                                <strong>Tindakan:</strong> Segera lakukan penyelidikan mendalam<br>
                                                <strong>Alokasi:</strong> Tim khusus + sumber daya prioritas
                                            </small>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-info">
                                            <small>Rekomendasi akan tersedia setelah perhitungan TOPSIS.</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-warning">Statistik Kasus:</h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border rounded p-2 mb-2">
                                                    <h5 class="text-danger mb-0">
                                                        <?php 
                                                        if ($totalLoss >= 1000000000) {
                                                            echo 'Rp ' . number_format($totalLoss / 1000000000, 1) . 'M';
                                                        } elseif ($totalLoss >= 1000000) {
                                                            echo 'Rp ' . number_format($totalLoss / 1000000, 1) . 'Jt';
                                                        } else {
                                                            echo 'Rp ' . number_format($totalLoss / 1000, 0) . 'K';
                                                        }
                                                        ?>
                                                    </h5>
                                                    <small class="text-muted">Total Kerugian</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2 mb-2">
                                                    <h5 class="text-primary mb-0"><?php echo $totalVictims; ?></h5>
                                                    <small class="text-muted">Total Korban</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2">
                                                    <h5 class="text-success mb-0"><?php echo number_format($avgUrgency, 1); ?></h5>
                                                    <small class="text-muted">Rata-rata Urgensi</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2">
                                                    <h5 class="text-warning mb-0"><?php echo number_format($avgSpread, 1); ?></h5>
                                                    <small class="text-muted">Rata-rata Penyebaran</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Client View - Simplified Analysis -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Ringkasan Hasil:</h6>
                                        <?php if ($topsisAvailable && count($topsisResults) > 0): ?>
                                        <div class="alert alert-success">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-trophy me-2"></i>Kasus Prioritas Tertinggi:
                                            </h6>
                                            <p class="mb-1"><strong><?php echo htmlspecialchars($topsisResults[0]['alternative_name']); ?></strong></p>
                                            <p class="mb-0">
                                                <small>Skor TOPSIS: <?php echo number_format($topsisResults[0]['closeness_coefficient'], 4); ?></small>
                                            </p>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-info-circle me-2"></i>Status Analisis:
                                            </h6>
                                            <ul class="mb-0">
                                                <li>Total kasus dianalisis: <?php echo $totalCases; ?></li>
                                                <li>Metode: AHP + TOPSIS</li>
                                                <li>Status: Selesai</li>
                                            </ul>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-warning">
                                            <h6 class="alert-heading">Belum Ada Hasil</h6>
                                            <p class="mb-0">Silakan lakukan perhitungan AHP dan TOPSIS terlebih dahulu.</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-success">Statistik Ringkas:</h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border rounded p-3 mb-3">
                                                    <h4 class="text-danger mb-1">
                                                        <?php 
                                                        if ($totalLoss >= 1000000000) {
                                                            echo 'Rp ' . number_format($totalLoss / 1000000000, 1) . 'M';
                                                        } elseif ($totalLoss >= 1000000) {
                                                            echo 'Rp ' . number_format($totalLoss / 1000000, 1) . 'Jt';
                                                        } else {
                                                            echo 'Rp ' . number_format($totalLoss / 1000, 0) . 'K';
                                                        }
                                                        ?>
                                                    </h4>
                                                    <small class="text-muted">Total Kerugian</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-3 mb-3">
                                                    <h4 class="text-primary mb-1"><?php echo $totalVictims; ?></h4>
                                                    <small class="text-muted">Total Korban</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-light">
                                            <h6 class="text-info">
                                                <i class="fas fa-lightbulb me-2"></i>Informasi:
                                            </h6>
                                            <p class="mb-0 small">
                                                Hasil analisis menunjukkan prioritas penanganan berdasarkan 
                                                metode ilmiah AHP dan TOPSIS. Kasus dengan ranking 1 memiliki 
                                                prioritas penanganan tertinggi.
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Recommendations -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-tasks me-2"></i>
                                Rekomendasi Tindakan
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <span class="badge bg-warning ms-2">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info ms-2">Client</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <!-- Admin View - Detailed Action Plan -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Prioritas 1: Phishing Banking
                                            </h6>
                                            <ul class="mb-2">
                                                <li>Lakukan penyelidikan segera</li>
                                                <li>Koordinasi dengan bank terkait</li>
                                                <li>Bekukan rekening tersangka</li>
                                                <li>Edukasi masyarakat</li>
                                            </ul>
                                            <small class="text-muted">
                                                <strong>Timeline:</strong> 24 jam<br>
                                                <strong>Tim:</strong> Reskrim + Cyber Crime
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-warning">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-clock me-2"></i>
                                                Prioritas 2: Investasi Bodong
                                            </h6>
                                            <ul class="mb-2">
                                                <li>Investigasi platform investasi</li>
                                                <li>Lacak aliran dana</li>
                                                <li>Koordinasi dengan OJK</li>
                                                <li>Peringatan publik</li>
                                            </ul>
                                            <small class="text-muted">
                                                <strong>Timeline:</strong> 3-7 hari<br>
                                                <strong>Tim:</strong> Reskrim + Ekonomi
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Prioritas 3: Penipuan Marketplace
                                            </h6>
                                            <ul class="mb-2">
                                                <li>Verifikasi laporan korban</li>
                                                <li>Koordinasi dengan platform</li>
                                                <li>Dokumentasi bukti</li>
                                                <li>Proses sesuai prosedur</li>
                                            </ul>
                                            <small class="text-muted">
                                                <strong>Timeline:</strong> 1-2 minggu<br>
                                                <strong>Tim:</strong> Reskrim Standar
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Admin Additional Info -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-secondary">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-clipboard-list me-2"></i>
                                                Catatan Khusus Admin:
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Alokasi Sumber Daya:</strong>
                                                    <ul class="mb-0">
                                                        <li>Prioritas 1: Tim khusus + overtime</li>
                                                        <li>Prioritas 2: Tim reguler + konsultan</li>
                                                        <li>Prioritas 3: Tim reguler</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Koordinasi Eksternal:</strong>
                                                    <ul class="mb-0">
                                                        <li>Bank Indonesia & perbankan</li>
                                                        <li>OJK & lembaga keuangan</li>
                                                        <li>Platform e-commerce</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Client View - Simplified Recommendations -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-shield-alt me-2"></i>
                                                Langkah Pencegahan:
                                            </h6>
                                            <ul class="mb-0">
                                                <li>Selalu verifikasi identitas penerima transfer</li>
                                                <li>Jangan mudah percaya investasi dengan return tinggi</li>
                                                <li>Gunakan platform e-commerce terpercaya</li>
                                                <li>Aktifkan notifikasi transaksi perbankan</li>
                                                <li>Laporkan segera jika menjadi korban</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-phone me-2"></i>
                                                Kontak Darurat:
                                            </h6>
                                            <div class="mb-2">
                                                <strong>Polsek Saribudolok:</strong><br>
                                                <i class="fas fa-phone me-1"></i> (0622) 123-4567<br>
                                                <i class="fas fa-envelope me-1"></i> polsek@saribudolok.go.id
                                            </div>
                                            <div class="mb-2">
                                                <strong>Cyber Crime:</strong><br>
                                                <i class="fas fa-phone me-1"></i> 110 (Hotline Polri)<br>
                                                <i class="fas fa-globe me-1"></i> patrolisiber.id
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Penting untuk Diketahui:
                                    </h6>
                                    <p class="mb-0">
                                        Hasil analisis ini menunjukkan prioritas penanganan kasus berdasarkan metode ilmiah. 
                                        Sebagai masyarakat, Anda dapat membantu dengan melaporkan kasus serupa dan 
                                        meningkatkan kewaspadaan terhadap modus kejahatan online yang sedang marak.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
            <?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if ($topsisAvailable): ?>
// Priority Distribution Chart
const ctx1 = document.getElementById('priorityDistributionChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Sangat Tinggi', 'Tinggi', 'Sedang'],
        datasets: [{
            data: [<?php echo $priorityCount['high']; ?>, <?php echo $priorityCount['medium']; ?>, <?php echo $priorityCount['low']; ?>],
            backgroundColor: [
                'rgba(220, 53, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(13, 202, 240, 0.8)'
            ],
            borderColor: [
                'rgba(220, 53, 69, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(13, 202, 240, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Score Comparison Chart
const ctx2 = document.getElementById('scoreComparisonChart').getContext('2d');
const chartLabels = [<?php foreach($topsisResults as $result) echo "'" . htmlspecialchars($result['alternative_id']) . "',"; ?>];
const topsisScores = [<?php foreach($topsisResults as $result) echo number_format($result['closeness_coefficient'], 4) . ","; ?>];

new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Skor TOPSIS',
            data: topsisScores,
            backgroundColor: 'rgba(75, 192, 192, 0.8)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 1
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
<?php endif; ?>

// Export functions
function exportToExcel() {
    <?php if ($topsisAvailable): ?>
    // Create CSV content
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Ranking,ID Kasus,Nama Kasus,Skor TOPSIS,Status Prioritas\n";
    
    <?php foreach ($topsisResults as $result): ?>
    csvContent += "<?php echo $result['ranking']; ?>,<?php echo htmlspecialchars($result['alternative_id']); ?>,<?php echo htmlspecialchars($result['alternative_name']); ?>,<?php echo number_format($result['closeness_coefficient'], 4); ?>,<?php echo ($result['ranking'] == 1) ? 'SANGAT TINGGI' : (($result['ranking'] == 2) ? 'TINGGI' : 'SEDANG'); ?>\n";
    <?php endforeach; ?>
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "hasil_analisis_prioritas_kasus.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    <?php else: ?>
    alert('Tidak ada data untuk diekspor. Lakukan perhitungan TOPSIS terlebih dahulu.');
    <?php endif; ?>
}

function printResults() {
    <?php if ($topsisAvailable): ?>
    window.print();
    <?php else: ?>
    alert('Tidak ada data untuk dicetak. Lakukan perhitungan TOPSIS terlebih dahulu.');
    <?php endif; ?>
}
</script>

<?php include '../includes/footer.php'; ?>
