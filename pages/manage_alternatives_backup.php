<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

require_once '../config/database.php';

// Handle form submissions BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_alternative'])) {
        $case_id = $_POST['case_id'];
        $alternative_name = $_POST['alternative_name'];
        $description = $_POST['description'];

        try {
            $stmt = $pdo->prepare("INSERT INTO alternatives (case_id, alternative_name, description) VALUES (?, ?, ?)");
            $stmt->execute([$case_id, $alternative_name, $description]);
            $_SESSION['success'] = "Alternatif berhasil ditambahkan!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal menambahkan alternatif: " . $e->getMessage();
        }
        header('Location: manage_alternatives.php');
        exit();
    }

    if (isset($_POST['update_alternative'])) {
        $id = $_POST['alternative_id'];
        $case_id = $_POST['case_id'];
        $alternative_name = $_POST['alternative_name'];
        $description = $_POST['description'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        try {
            $stmt = $pdo->prepare("UPDATE alternatives SET case_id = ?, alternative_name = ?, description = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$case_id, $alternative_name, $description, $is_active, $id]);
            $_SESSION['success'] = "Alternatif berhasil diupdate!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal mengupdate alternatif: " . $e->getMessage();
        }
        header('Location: manage_alternatives.php');
        exit();
    }
}

// Handle delete BEFORE including header
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $alternative_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM alternatives WHERE id = ?");
        $stmt->execute([$alternative_id]);
        $_SESSION['success'] = "Alternatif berhasil dihapus!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menghapus alternatif: " . $e->getMessage();
    }
    header('Location: manage_alternatives.php');
    exit();
}

$page_title = 'Kelola Data dan Alternatif - Admin Panel';
include '../includes/header.php';

// Ambil semua kasus untuk dropdown
try {
    $stmt = $pdo->query("SELECT * FROM cases ORDER BY case_number ASC");
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $cases = [];
}

// Ambil semua alternatif dengan join ke tabel cases
try {
    $stmt = $pdo->query("
        SELECT a.*, c.case_number, c.case_name, c.case_type, c.estimated_loss, c.status 
        FROM alternatives a 
        JOIN cases c ON a.case_id = c.id 
        ORDER BY c.case_number ASC, a.alternative_name ASC
    ");
    $alternatives = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $alternatives = [];
    $_SESSION['error'] = "Gagal mengambil data alternatif: " . $e->getMessage();
}

// Statistik alternatif per status kasus
$stats_by_status = [];
foreach ($alternatives as $alt) {
    $status = $alt['status'];
    if (!isset($stats_by_status[$status])) {
        $stats_by_status[$status] = 0;
    }
    $stats_by_status[$status]++;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Include Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-database me-2 text-primary"></i>
                    Kelola Data dan Alternatif
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                        <i class="fas fa-plus me-1"></i>Tambah Alternatif
                    </button>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Info Panel -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Alternatif
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Alternatif</strong> adalah pilihan-pilihan kasus kejahatan online yang akan diprioritaskan menggunakan metode AHP dan TOPSIS.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Fungsi Alternatif:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Representasi kasus dalam sistem SPK</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Objek yang akan dievaluasi dan diranking</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Dasar perhitungan prioritas penanganan</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Proses Evaluasi:</h6>
                                    <p class="small text-muted">
                                        Setiap alternatif akan dinilai berdasarkan kriteria yang telah ditetapkan, 
                                        kemudian diproses menggunakan metode AHP untuk pembobotan dan TOPSIS untuk perangkingan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Alternatif</h6>
                            <h3><?php echo count($alternatives); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Alternatif Aktif</h6>
                            <h3><?php echo count(array_filter($alternatives, function($a) { return $a['is_active']; })); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Kasus Pending</h6>
                            <h3><?php echo isset($stats_by_status['pending']) ? $stats_by_status['pending'] : 0; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Kasus</h6>
                            <h3><?php echo count($cases); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alternatives Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Daftar Alternatif Kasus
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No. Kasus</th>
                                    <th>Nama Alternatif</th>
                                    <th>Jenis Kasus</th>
                                    <th>Estimasi Kerugian</th>
                                    <th>Status Kasus</th>
                                    <th>Deskripsi</th>
                                    <th>Status Alternatif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($alternatives) > 0): ?>
                                    <?php foreach ($alternatives as $alternative): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($alternative['case_number']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($alternative['case_name']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($alternative['alternative_name']); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo ucfirst(str_replace('_', ' ', $alternative['case_type'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>Rp <?php echo number_format($alternative['estimated_loss'], 0, ',', '.'); ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch($alternative['status']) {
                                                    case 'pending': $status_class = 'bg-warning'; break;
                                                    case 'investigating': $status_class = 'bg-primary'; break;
                                                    case 'resolved': $status_class = 'bg-success'; break;
                                                    case 'closed': $status_class = 'bg-secondary'; break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $status_class; ?>">
                                                    <?php echo ucfirst($alternative['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <?php echo strlen($alternative['description']) > 50 ?
                                                        substr(htmlspecialchars($alternative['description']), 0, 50) . '...' :
                                                        htmlspecialchars($alternative['description']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($alternative['is_active']): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewAlternativeModal<?php echo $alternative['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAlternativeModal<?php echo $alternative['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?delete=<?php echo $alternative['id']; ?>"
                                                       class="btn btn-danger"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus alternatif ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data alternatif</p>
                                                <p class="text-muted small">Silakan tambahkan kasus terlebih dahulu</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modals Section - Moved outside table for clean structure -->
            <?php if (count($alternatives) > 0): ?>
                <?php foreach ($alternatives as $alternative): ?>

                    <!-- View Alternative Modal -->
                    <div class="modal fade" id="viewAlternativeModal<?php echo $alternative['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-eye me-2"></i>
                                        Detail Alternatif
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Informasi Kasus:</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>No. Kasus:</strong></td>
                                                    <td><?php echo htmlspecialchars($alternative['case_number']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama Kasus:</strong></td>
                                                    <td><?php echo htmlspecialchars($alternative['case_name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jenis:</strong></td>
                                                    <td><?php echo ucfirst(str_replace('_', ' ', $alternative['case_type'])); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kerugian:</strong></td>
                                                    <td>Rp <?php echo number_format($alternative['estimated_loss'], 0, ',', '.'); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Informasi Alternatif:</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Nama Alternatif:</strong></td>
                                                    <td><?php echo htmlspecialchars($alternative['alternative_name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>
                                                        <?php if ($alternative['is_active']): ?>
                                                            <span class="badge bg-success">Aktif</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Nonaktif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dibuat:</strong></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($alternative['created_at'])); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-primary">Deskripsi Alternatif:</h6>
                                        <p class="text-muted"><?php echo htmlspecialchars($alternative['description']); ?></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Alternative Modal -->
                    <div class="modal fade" id="editAlternativeModal<?php echo $alternative['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit me-2"></i>
                                        Edit Alternatif
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="alternative_id" value="<?php echo $alternative['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Kasus Terkait</label>
                                            <select name="case_id" class="form-select" required>
                                                <?php foreach ($cases as $case): ?>
                                                    <option value="<?php echo $case['id']; ?>"
                                                            <?php echo $case['id'] == $alternative['case_id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($case['case_number'] . ' - ' . $case['case_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Alternatif</label>
                                            <input type="text" name="alternative_name" class="form-control"
                                                   value="<?php echo htmlspecialchars($alternative['alternative_name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi Alternatif</label>
                                            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($alternative['description']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" class="form-check-input"
                                                       <?php echo $alternative['is_active'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label">Alternatif Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="update_alternative" class="btn btn-warning">
                                            <i class="fas fa-save me-1"></i>Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Add Alternative Modal -->
            <div class="modal fade" id="addAlternativeModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Alternatif Baru
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Kasus</label>
                                    <select name="case_id" class="form-select" required>
                                        <option value="">Pilih Kasus yang Akan Dijadikan Alternatif</option>
                                        <?php foreach ($cases as $case): ?>
                                            <option value="<?php echo $case['id']; ?>">
                                                <?php echo htmlspecialchars($case['case_number'] . ' - ' . $case['case_name']); ?>
                                                (<?php echo ucfirst(str_replace('_', ' ', $case['case_type'])); ?> - 
                                                Rp <?php echo number_format($case['estimated_loss'], 0, ',', '.'); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Pilih kasus yang akan dijadikan alternatif untuk evaluasi prioritas</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Alternatif</label>
                                    <input type="text" name="alternative_name" class="form-control" 
                                           placeholder="Contoh: Kasus Penipuan Online A1" required>
                                    <div class="form-text">Berikan nama yang mudah diidentifikasi untuk alternatif ini</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Alternatif</label>
                                    <textarea name="description" class="form-control" rows="4" 
                                              placeholder="Jelaskan mengapa kasus ini dijadikan alternatif dan karakteristik khususnya..."></textarea>
                                    <div class="form-text">Opsional: Deskripsi tambahan tentang alternatif ini</div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-lightbulb me-2"></i>Tips Pembuatan Alternatif:
                                    </h6>
                                    <ul class="mb-0">
                                        <li>Pilih kasus yang representatif dan memiliki karakteristik berbeda</li>
                                        <li>Pastikan data kasus lengkap untuk evaluasi yang akurat</li>
                                        <li>Alternatif akan dievaluasi berdasarkan kriteria yang telah ditetapkan</li>
                                        <li>Hasil evaluasi akan menentukan prioritas penanganan kasus</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="add_alternative" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Tambah Alternatif
                                </button>
                            </div>
                        </form>
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

<script>
// Auto-generate nama alternatif berdasarkan kasus yang dipilih
document.querySelector('select[name="case_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        const caseText = selectedOption.text;
        const caseNumber = caseText.split(' - ')[0];
        const nameInput = document.querySelector('input[name="alternative_name"]');
        
        if (!nameInput.value) {
            nameInput.value = 'Alternatif ' + caseNumber;
        }
    }
});

// Konfirmasi sebelum menghapus
document.addEventListener('DOMContentLoaded', function() {
    const deleteLinks = document.querySelectorAll('a[href*="delete="]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus alternatif ini?\n\nTindakan ini tidak dapat dibatalkan dan akan mempengaruhi perhitungan prioritas.')) {
                e.preventDefault();
            }
        });
    });
});
</script>

</body>
</html>
