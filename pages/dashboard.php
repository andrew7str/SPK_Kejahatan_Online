<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$page_title = 'Dashboard - Sistem Pendukung Keputusan';
include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Include Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="p-4">
                    <!-- Alert Messages -->
                    <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); endif; ?>

                    <!-- Dashboard Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Dashboard</h1>
                        <div class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <span class="current-time"><?php echo date('d/m/Y H:i:s'); ?></span>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Kasus</h6>
                                            <?php
                                            try {
                                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM cases");
                                                $total_cases = $stmt->fetch()['total'];
                                            } catch (Exception $e) {
                                                $total_cases = 0;
                                            }
                                            ?>
                                            <h3><?php echo $total_cases; ?></h3>
                                        </div>
                                        <div>
                                            <i class="fas fa-folder fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Kasus Selesai</h6>
                                            <?php
                                            try {
                                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM cases WHERE status = 'resolved'");
                                                $resolved_cases = $stmt->fetch()['total'];
                                            } catch (Exception $e) {
                                                $resolved_cases = 0;
                                            }
                                            ?>
                                            <h3><?php echo $resolved_cases; ?></h3>
                                        </div>
                                        <div>
                                            <i class="fas fa-check-circle fa-2x"></i>
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
                                            <h6 class="card-title">Dalam Proses</h6>
                                            <?php
                                            try {
                                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM cases WHERE status = 'investigating'");
                                                $investigating_cases = $stmt->fetch()['total'];
                                            } catch (Exception $e) {
                                                $investigating_cases = 0;
                                            }
                                            ?>
                                            <h3><?php echo $investigating_cases; ?></h3>
                                        </div>
                                        <div>
                                            <i class="fas fa-spinner fa-2x"></i>
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
                                            <h6 class="card-title">Pending</h6>
                                            <?php
                                            try {
                                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM cases WHERE status = 'pending'");
                                                $pending_cases = $stmt->fetch()['total'];
                                            } catch (Exception $e) {
                                                $pending_cases = 0;
                                            }
                                            ?>
                                            <h3><?php echo $pending_cases; ?></h3>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Cases -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-list me-2"></i>Kasus Terbaru
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT * FROM cases ORDER BY created_at DESC LIMIT 5");
                                        $recent_cases = $stmt->fetchAll();
                                    } catch (Exception $e) {
                                        $recent_cases = [];
                                    }
                                    ?>
                                    
                                    <?php if (count($recent_cases) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No. Kasus</th>
                                                    <th>Nama Kasus</th>
                                                    <th>Jenis</th>
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
                                                    <th>Kerugian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_cases as $case): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($case['case_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($case['case_name']); ?></td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            <?php echo ucfirst(str_replace('_', ' ', $case['case_type'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status_class = '';
                                                        switch($case['status']) {
                                                            case 'pending': $status_class = 'bg-warning'; break;
                                                            case 'investigating': $status_class = 'bg-primary'; break;
                                                            case 'resolved': $status_class = 'bg-success'; break;
                                                            case 'closed': $status_class = 'bg-secondary'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $status_class; ?>">
                                                            <?php echo ucfirst($case['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($case['report_date'])); ?></td>
                                                    <td>Rp <?php echo number_format($case['estimated_loss'], 0, ',', '.'); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada data kasus</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="ahp.php" class="btn btn-primary w-100 mb-2">
                                                <i class="fas fa-calculator me-2"></i>
                                                Mulai AHP
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="topsis.php" class="btn btn-success w-100 mb-2">
                                                <i class="fas fa-chart-line me-2"></i>
                                                Mulai TOPSIS
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="results.php" class="btn btn-info w-100 mb-2">
                                                <i class="fas fa-trophy me-2"></i>
                                                Lihat Hasil
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning w-100 mb-2" onclick="window.print()">
                                                <i class="fas fa-print me-2"></i>
                                                Cetak Laporan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="../assets/js/script.js"></script>
</body>
</html>
