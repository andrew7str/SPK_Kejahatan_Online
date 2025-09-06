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

$page_title = 'Kelola Data dan Sub Kriteria - Admin Panel';
include '../includes/header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_sub_criteria'])) {
        $criteria_id = $_POST['criteria_id'];
        $code = $_POST['code'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $score_range = $_POST['score_range'];
        $weight = $_POST['weight'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO sub_criteria (criteria_id, code, name, description, score_range, weight) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$criteria_id, $code, $name, $description, $score_range, $weight]);
            $_SESSION['success'] = "Sub kriteria berhasil ditambahkan!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal menambahkan sub kriteria: " . $e->getMessage();
        }
        header('Location: manage_sub_criteria.php');
        exit();
    }
    
    if (isset($_POST['update_sub_criteria'])) {
        $id = $_POST['sub_criteria_id'];
        $criteria_id = $_POST['criteria_id'];
        $code = $_POST['code'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $score_range = $_POST['score_range'];
        $weight = $_POST['weight'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        try {
            $stmt = $pdo->prepare("UPDATE sub_criteria SET criteria_id = ?, code = ?, name = ?, description = ?, score_range = ?, weight = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$criteria_id, $code, $name, $description, $score_range, $weight, $is_active, $id]);
            $_SESSION['success'] = "Sub kriteria berhasil diupdate!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal mengupdate sub kriteria: " . $e->getMessage();
        }
        header('Location: manage_sub_criteria.php');
        exit();
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $sub_criteria_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM sub_criteria WHERE id = ?");
        $stmt->execute([$sub_criteria_id]);
        $_SESSION['success'] = "Sub kriteria berhasil dihapus!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menghapus sub kriteria: " . $e->getMessage();
    }
    header('Location: manage_sub_criteria.php');
    exit();
}

// Ambil semua kriteria untuk dropdown
try {
    $stmt = $pdo->query("SELECT * FROM criteria WHERE is_active = 1 ORDER BY code ASC");
    $criteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $criteria = [];
}

// Ambil semua sub kriteria dengan join ke tabel criteria
try {
    $stmt = $pdo->query("
        SELECT sc.*, c.name as criteria_name, c.code as criteria_code 
        FROM sub_criteria sc 
        JOIN criteria c ON sc.criteria_id = c.id 
        ORDER BY c.code ASC, sc.code ASC
    ");
    $sub_criteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $sub_criteria = [];
    $_SESSION['error'] = "Gagal mengambil data sub kriteria: " . $e->getMessage();
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
                    <i class="fas fa-list me-2 text-primary"></i>
                    Kelola Data dan Sub Kriteria
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubCriteriaModal">
                        <i class="fas fa-plus me-1"></i>Tambah Sub Kriteria
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
                                Informasi Sub Kriteria
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Sub Kriteria</strong> adalah turunan dari kriteria utama yang memberikan penilaian lebih detail dan spesifik.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Rentang Skor:</h6>
                                    <ul class="list-unstyled">
                                        <li><span class="badge bg-danger me-2">1</span>Sangat Rendah/Buruk</li>
                                        <li><span class="badge bg-warning me-2">2</span>Rendah</li>
                                        <li><span class="badge bg-info me-2">3</span>Sedang</li>
                                        <li><span class="badge bg-primary me-2">4</span>Tinggi</li>
                                        <li><span class="badge bg-success me-2">5</span>Sangat Tinggi/Baik</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Contoh Sub Kriteria:</h6>
                                    <p class="small text-muted">
                                        Untuk kriteria "Tingkat Kerugian" dapat memiliki sub kriteria:<br>
                                        - Kerugian Rendah (< 10 juta)<br>
                                        - Kerugian Sedang (10-50 juta)<br>
                                        - Kerugian Tinggi (> 50 juta)
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
                            <h6 class="card-title">Total Sub Kriteria</h6>
                            <h3><?php echo count($sub_criteria); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Sub Kriteria Aktif</h6>
                            <h3><?php echo count(array_filter($sub_criteria, function($sc) { return $sc['is_active']; })); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Kriteria Induk</h6>
                            <h3><?php echo count($criteria); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Rata-rata per Kriteria</h6>
                            <h3><?php echo count($criteria) > 0 ? number_format(count($sub_criteria) / count($criteria), 1) : 0; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Criteria Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Daftar Sub Kriteria
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kriteria Induk</th>
                                    <th>Kode Sub</th>
                                    <th>Nama Sub Kriteria</th>
                                    <th>Deskripsi</th>
                                    <th>Rentang Skor</th>
                                    <th>Bobot</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($sub_criteria) > 0): ?>
                                    <?php foreach ($sub_criteria as $sub_criterion): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($sub_criterion['criteria_code']); ?></span>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($sub_criterion['criteria_name']); ?></small>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($sub_criterion['code']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($sub_criterion['name']); ?></td>
                                            <td>
                                                <span class="text-muted">
                                                    <?php echo strlen($sub_criterion['description']) > 40 ? 
                                                        substr(htmlspecialchars($sub_criterion['description']), 0, 40) . '...' : 
                                                        htmlspecialchars($sub_criterion['description']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($sub_criterion['score_range']); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo number_format($sub_criterion['weight'], 4); ?></span>
                                            </td>
                                            <td>
                                                <?php if ($sub_criterion['is_active']): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-warning edit-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editSubCriteriaModal<?php echo $sub_criterion['id']; ?>"
                                                            data-sub-criteria-id="<?php echo $sub_criterion['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?delete=<?php echo $sub_criterion['id']; ?>"
                                                       class="btn btn-danger"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus sub kriteria ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Sub Criteria Modal -->
                                        <div class="modal fade" id="editSubCriteriaModal<?php echo $sub_criterion['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit me-2"></i>
                                                            Edit Sub Kriteria
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="sub_criteria_id" value="<?php echo $sub_criterion['id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Kriteria Induk</label>
                                                                        <select name="criteria_id" class="form-select" required>
                                                                            <?php foreach ($criteria as $criterion): ?>
                                                                                <option value="<?php echo $criterion['id']; ?>" 
                                                                                        <?php echo $criterion['id'] == $sub_criterion['criteria_id'] ? 'selected' : ''; ?>>
                                                                                    <?php echo htmlspecialchars($criterion['code'] . ' - ' . $criterion['name']); ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Kode Sub Kriteria</label>
                                                                        <input type="text" name="code" class="form-control" 
                                                                               value="<?php echo htmlspecialchars($sub_criterion['code']); ?>" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Sub Kriteria</label>
                                                                <input type="text" name="name" class="form-control" 
                                                                       value="<?php echo htmlspecialchars($sub_criterion['name']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Deskripsi</label>
                                                                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($sub_criterion['description']); ?></textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Rentang Skor</label>
                                                                        <input type="text" name="score_range" class="form-control" 
                                                                               value="<?php echo htmlspecialchars($sub_criterion['score_range']); ?>" 
                                                                               placeholder="1-5">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Bobot</label>
                                                                        <input type="number" name="weight" class="form-control" 
                                                                               step="0.0001" min="0" max="1" 
                                                                               value="<?php echo $sub_criterion['weight']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <div class="form-check mt-4">
                                                                            <input type="checkbox" name="is_active" class="form-check-input" 
                                                                                   <?php echo $sub_criterion['is_active'] ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label">Sub Kriteria Aktif</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="update_sub_criteria" class="btn btn-warning">
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
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data sub kriteria</p>
                                                <p class="text-muted small">Silakan tambahkan kriteria utama terlebih dahulu</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Sub Criteria Modal -->
            <div class="modal fade" id="addSubCriteriaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Sub Kriteria Baru
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kriteria Induk</label>
                                            <select name="criteria_id" class="form-select" required>
                                                <option value="">Pilih Kriteria Induk</option>
                                                <?php foreach ($criteria as $criterion): ?>
                                                    <option value="<?php echo $criterion['id']; ?>">
                                                        <?php echo htmlspecialchars($criterion['code'] . ' - ' . $criterion['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Sub Kriteria</label>
                                            <input type="text" name="code" class="form-control" placeholder="Contoh: C1.1" required>
                                            <div class="form-text">Format: [Kode Kriteria].[Nomor] (contoh: C1.1, C1.2)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Sub Kriteria</label>
                                    <input type="text" name="name" class="form-control" placeholder="Contoh: Kerugian Rendah" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="3" 
                                              placeholder="Jelaskan sub kriteria ini secara detail..."></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Rentang Skor</label>
                                            <select name="score_range" class="form-select" required>
                                                <option value="">Pilih Rentang</option>
                                                <option value="1">1 - Sangat Rendah</option>
                                                <option value="2">2 - Rendah</option>
                                                <option value="3">3 - Sedang</option>
                                                <option value="4">4 - Tinggi</option>
                                                <option value="5">5 - Sangat Tinggi</option>
                                                <option value="1-5">1-5 - Rentang Penuh</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bobot (0.0000 - 1.0000)</label>
                                            <input type="number" name="weight" class="form-control" step="0.0001" min="0" max="1" 
                                                   placeholder="0.0000" value="0.0000">
                                            <div class="form-text">Opsional: Bobot khusus untuk sub kriteria</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="add_sub_criteria" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Tambah Sub Kriteria
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

<style>
/* Prevent modal blink on page load - less aggressive approach */
.modal:not(.show) {
    display: none !important;
}

/* Ensure shown modals are visible */
.modal.show {
    display: block !important;
}
</style>

<script>
// Auto-generate kode sub kriteria berdasarkan kriteria yang dipilih
document.querySelector('select[name="criteria_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        const criteriaCode = selectedOption.text.split(' - ')[0];
        const codeInput = document.querySelector('input[name="code"]');
        
        // Hitung jumlah sub kriteria yang sudah ada untuk kriteria ini
        const existingSubCriteria = <?php echo json_encode($sub_criteria); ?>;
        const existingCodes = existingSubCriteria
            .filter(sc => sc.criteria_code === criteriaCode)
            .map(sc => sc.code);
        
        let nextNumber = 1;
        while (existingCodes.includes(criteriaCode + '.' + nextNumber)) {
            nextNumber++;
        }
        
        codeInput.value = criteriaCode + '.' + nextNumber;
    }
});

// Validasi bobot
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

    // Handle edit button clicks with Bootstrap
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Add loading state to prevent multiple clicks
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            // Re-enable button after modal is shown
            setTimeout(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-edit"></i>';
            }, 300);
        });
    });

    // Ensure Bootstrap modal events work properly
    document.addEventListener('shown.bs.modal', function(e) {
        // Modal is now shown, ensure buttons are re-enabled
        const buttons = document.querySelectorAll('.edit-btn:disabled');
        buttons.forEach(button => {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-edit"></i>';
        });
    });

    // Handle form submission with loading state
    const editForms = document.querySelectorAll('form');
    editForms.forEach(form => {
        if (form.querySelector('input[name="update_sub_criteria"]')) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

                    // Close modal immediately to prevent blink
                    const modal = this.closest('.modal');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    }
                }
            });
        }
    });

    // Reset modal state when modal is hidden
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            // Reset any disabled buttons
            const buttons = this.querySelectorAll('button:disabled');
            buttons.forEach(button => {
                if (button.querySelector('.fa-spinner')) {
                    button.disabled = false;
                    button.innerHTML = button.innerHTML.replace(
                        '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...',
                        '<i class="fas fa-save me-1"></i>Update'
                    );
                }
            });
        });

        // Prevent modal from showing on page load/refresh
        modal.addEventListener('show.bs.modal', function(e) {
            // Only allow modal to show if triggered by user action, not page load
            if (!e.relatedTarget && !e.target.classList.contains('edit-btn')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Hide all modals immediately on page load to prevent blink
    window.addEventListener('load', function() {
        const allModals = document.querySelectorAll('.modal');
        allModals.forEach(modal => {
            modal.style.display = 'none';
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        });
    });

    // Additional prevention for modal blink
    document.addEventListener('DOMContentLoaded', function() {
        // Force hide all modals
        setTimeout(() => {
            const allModals = document.querySelectorAll('.modal');
            allModals.forEach(modal => {
                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            });
        }, 100);
    });
});
</script>

</body>
</html>
