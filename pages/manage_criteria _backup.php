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

$page_title = 'Kelola Data dan Kriteria - Admin Panel';
include '../includes/header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_criteria'])) {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $weight = $_POST['weight'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO criteria (code, name, description, type, weight) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$code, $name, $description, $type, $weight]);
            $_SESSION['success'] = "Kriteria berhasil ditambahkan!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal menambahkan kriteria: " . $e->getMessage();
        }
        header('Location: manage_criteria.php');
        exit();
    }
    
    if (isset($_POST['update_criteria'])) {
        $id = $_POST['criteria_id'];
        $code = $_POST['code'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $weight = $_POST['weight'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        try {
            $stmt = $pdo->prepare("UPDATE criteria SET code = ?, name = ?, description = ?, type = ?, weight = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$code, $name, $description, $type, $weight, $is_active, $id]);
            $_SESSION['success'] = "Kriteria berhasil diupdate!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal mengupdate kriteria: " . $e->getMessage();
        }
        header('Location: manage_criteria.php');
        exit();
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $criteria_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM criteria WHERE id = ?");
        $stmt->execute([$criteria_id]);
        $_SESSION['success'] = "Kriteria berhasil dihapus!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menghapus kriteria: " . $e->getMessage();
    }
    header('Location: manage_criteria.php');
    exit();
}

// Ambil semua kriteria dari database
try {
    $stmt = $pdo->query("SELECT * FROM criteria ORDER BY code ASC");
    $criteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $criteria = [];
    $_SESSION['error'] = "Gagal mengambil data kriteria: " . $e->getMessage();
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
                    <i class="fas fa-list-alt me-2 text-primary"></i>
                    Kelola Data dan Kriteria
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
                        <i class="fas fa-plus me-1"></i>Tambah Kriteria
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
                                Informasi Kriteria AHP
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Kriteria</strong> adalah faktor-faktor yang digunakan untuk mengevaluasi dan membandingkan kasus kejahatan online.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Jenis Kriteria:</h6>
                                    <ul class="list-unstyled">
                                        <li><span class="badge bg-success me-2">Benefit</span>Semakin tinggi nilai semakin baik</li>
                                        <li><span class="badge bg-warning me-2">Cost</span>Semakin rendah nilai semakin baik</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Bobot Kriteria:</h6>
                                    <p class="small text-muted">Bobot menunjukkan tingkat kepentingan relatif kriteria. Total bobot semua kriteria harus = 1.0</p>
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
                            <h6 class="card-title">Total Kriteria</h6>
                            <h3><?php echo count($criteria); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Kriteria Aktif</h6>
                            <h3><?php echo count(array_filter($criteria, function($c) { return $c['is_active']; })); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Bobot</h6>
                            <h3><?php echo number_format(array_sum(array_column($criteria, 'weight')), 3); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Kriteria Benefit</h6>
                            <h3><?php echo count(array_filter($criteria, function($c) { return $c['type'] == 'benefit'; })); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Criteria Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Daftar Kriteria
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Deskripsi</th>
                                    <th>Jenis</th>
                                    <th>Bobot</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($criteria) > 0): ?>
                                    <?php foreach ($criteria as $criterion): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($criterion['code']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($criterion['name']); ?></td>
                                            <td>
                                                <span class="text-muted">
                                                    <?php echo strlen($criterion['description']) > 50 ? 
                                                        substr(htmlspecialchars($criterion['description']), 0, 50) . '...' : 
                                                        htmlspecialchars($criterion['description']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($criterion['type'] == 'benefit'): ?>
                                                    <span class="badge bg-success">Benefit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Cost</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo number_format($criterion['weight'], 4); ?></span>
                                            </td>
                                            <td>
                                                <?php if ($criterion['is_active']): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editCriteriaModal<?php echo $criterion['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?delete=<?php echo $criterion['id']; ?>" 
                                                       class="btn btn-danger"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Criteria Modal -->
                                        <div class="modal fade" id="editCriteriaModal<?php echo $criterion['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit me-2"></i>
                                                            Edit Kriteria
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="criteria_id" value="<?php echo $criterion['id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Kode Kriteria</label>
                                                                        <input type="text" name="code" class="form-control" 
                                                                               value="<?php echo htmlspecialchars($criterion['code']); ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Jenis Kriteria</label>
                                                                        <select name="type" class="form-select" required>
                                                                            <option value="benefit" <?php echo $criterion['type'] == 'benefit' ? 'selected' : ''; ?>>Benefit</option>
                                                                            <option value="cost" <?php echo $criterion['type'] == 'cost' ? 'selected' : ''; ?>>Cost</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Kriteria</label>
                                                                <input type="text" name="name" class="form-control" 
                                                                       value="<?php echo htmlspecialchars($criterion['name']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Deskripsi</label>
                                                                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($criterion['description']); ?></textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Bobot (0.0000 - 1.0000)</label>
                                                                        <input type="number" name="weight" class="form-control" 
                                                                               step="0.0001" min="0" max="1" 
                                                                               value="<?php echo $criterion['weight']; ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <div class="form-check mt-4">
                                                                            <input type="checkbox" name="is_active" class="form-check-input" 
                                                                                   <?php echo $criterion['is_active'] ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label">Kriteria Aktif</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="update_criteria" class="btn btn-warning">
                                                                <i class="fas fa-save me-1"></i>Update
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data kriteria</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Criteria Modal -->
            <div class="modal fade" id="addCriteriaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Kriteria Baru
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Kriteria</label>
                                            <input type="text" name="code" class="form-control" placeholder="Contoh: C5" required>
                                            <div class="form-text">Kode unik untuk kriteria (contoh: C1, C2, dst.)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kriteria</label>
                                            <select name="type" class="form-select" required>
                                                <option value="">Pilih Jenis</option>
                                                <option value="benefit">Benefit (semakin tinggi semakin baik)</option>
                                                <option value="cost">Cost (semakin rendah semakin baik)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Kriteria</label>
                                    <input type="text" name="name" class="form-control" placeholder="Contoh: Kompleksitas Kasus" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="3" 
                                              placeholder="Jelaskan kriteria ini secara detail..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bobot (0.0000 - 1.0000)</label>
                                    <input type="number" name="weight" class="form-control" step="0.0001" min="0" max="1" 
                                           placeholder="0.0000" required>
                                    <div class="form-text">Bobot menunjukkan tingkat kepentingan kriteria. Total semua bobot harus = 1.0</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="add_criteria" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Tambah Kriteria
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
// Validasi bobot total
document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('input[name="weight"]');
    
    weightInputs.forEach(input => {
        input.addEventListener('input', function() {
            const currentValue = parseFloat(this.value) || 0;
            if (currentValue > 1) {
                this.value = 1;
                alert('Bobot tidak boleh lebih dari 1.0');
            }
        });
    });
});

// Auto-generate kode kriteria
document.querySelector('input[name="code"]').addEventListener('focus', function() {
    if (!this.value) {
        const existingCodes = <?php echo json_encode(array_column($criteria, 'code')); ?>;
        let nextNumber = 1;
        while (existingCodes.includes('C' + nextNumber)) {
            nextNumber++;
        }
        this.value = 'C' + nextNumber;
    }
});
</script>

</body>
</html>
