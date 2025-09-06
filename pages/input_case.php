<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../config/database.php';

$page_title = 'Input Kasus - Sistem Pendukung Keputusan';
include '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_case'])) {
    $case_number = $_POST['case_number'];
    $case_name = $_POST['case_name'];
    $case_type = $_POST['case_type'];
    $description = $_POST['description'];
    $reporter_name = $_POST['reporter_name'];
    $reporter_contact = $_POST['reporter_contact'];
    $incident_date = $_POST['incident_date'];
    $estimated_loss = $_POST['estimated_loss'];
    $victim_count = $_POST['victim_count'];
    $urgency_level = $_POST['urgency_level'];
    $spread_potential = $_POST['spread_potential'];
    $assigned_officer = $_POST['assigned_officer'];
    $priority_level = $_POST['priority_level'];
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO cases (
                case_number, case_name, case_type, description, reporter_name, reporter_contact,
                incident_date, estimated_loss, victim_count, urgency_level, spread_potential,
                assigned_officer, priority_level, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $case_number, $case_name, $case_type, $description, $reporter_name, $reporter_contact,
            $incident_date, $estimated_loss, $victim_count, $urgency_level, $spread_potential,
            $assigned_officer, $priority_level, $_SESSION['user_id']
        ]);
        
        $_SESSION['success'] = "Kasus berhasil ditambahkan dengan nomor: " . $case_number;
        header('Location: input_case.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menambahkan kasus: " . $e->getMessage();
    }
}

// Generate nomor kasus otomatis
$current_year = date('Y');
$current_month = date('m');
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM cases WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?");
    $stmt->execute([$current_year, $current_month]);
    $count = $stmt->fetch()['total'] + 1;
    $auto_case_number = "KASUS" . str_pad($count, 3, '0', STR_PAD_LEFT) . "/" . $current_year;
} catch (Exception $e) {
    $auto_case_number = "KASUS001/" . $current_year;
}

// Ambil daftar kasus terbaru untuk ditampilkan
try {
    $stmt = $pdo->query("
        SELECT c.*, u.username as created_by_name 
        FROM cases c 
        LEFT JOIN users u ON c.created_by = u.id 
        ORDER BY c.created_at DESC 
        LIMIT 10
    ");
    $recent_cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $recent_cases = [];
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
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    Input Kasus Kejahatan Online
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo me-1"></i>Reset Form
                        </button>
                    </div>
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

            <div class="row">
                <!-- Form Input Kasus -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-plus me-2"></i>
                                Form Input Kasus Baru
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="caseForm">
                                <!-- Informasi Dasar Kasus -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2">
                                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar Kasus
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Kasus</label>
                                            <input type="text" name="case_number" class="form-control" 
                                                   value="<?php echo $auto_case_number; ?>" required>
                                            <div class="form-text">Nomor kasus akan di-generate otomatis</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kejahatan</label>
                                            <select name="case_type" class="form-select" required>
                                                <option value="">Pilih Jenis Kejahatan</option>
                                                <option value="phishing">Phishing</option>
                                                <option value="hacking">Hacking</option>
                                                <option value="fraud">Penipuan Online</option>
                                                <option value="cyberbullying">Cyberbullying</option>
                                                <option value="identity_theft">Pencurian Identitas</option>
                                                <option value="online_scam">Penipuan Online</option>
                                                <option value="malware">Malware</option>
                                                <option value="other">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nama/Judul Kasus</label>
                                            <input type="text" name="case_name" class="form-control" 
                                                   placeholder="Contoh: Penipuan Online Investasi Bodong" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi Kasus</label>
                                            <textarea name="description" class="form-control" rows="4" 
                                                      placeholder="Jelaskan kronologi dan detail kasus secara lengkap..." required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Pelapor -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2">
                                            <i class="fas fa-user me-2"></i>Informasi Pelapor
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Pelapor</label>
                                            <input type="text" name="reporter_name" class="form-control" 
                                                   placeholder="Nama lengkap pelapor" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kontak Pelapor</label>
                                            <input type="text" name="reporter_contact" class="form-control" 
                                                   placeholder="No. HP / Email pelapor" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Kasus -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2">
                                            <i class="fas fa-calendar-alt me-2"></i>Detail Kejadian
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Kejadian</label>
                                            <input type="date" name="incident_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Estimasi Kerugian (Rp)</label>
                                            <input type="number" name="estimated_loss" class="form-control" 
                                                   placeholder="0" min="0" step="1000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Korban</label>
                                            <input type="number" name="victim_count" class="form-control" 
                                                   placeholder="1" min="1" value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Petugas yang Ditugaskan</label>
                                            <input type="text" name="assigned_officer" class="form-control" 
                                                   placeholder="Nama petugas penyidik">
                                        </div>
                                    </div>
                                </div>

                                <!-- Penilaian Kriteria -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2">
                                            <i class="fas fa-star me-2"></i>Penilaian Kriteria (Skala 1-5)
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tingkat Urgensi</label>
                                            <select name="urgency_level" class="form-select" required>
                                                <option value="">Pilih Tingkat Urgensi</option>
                                                <option value="1">1 - Sangat Rendah (dapat ditangani > 1 bulan)</option>
                                                <option value="2">2 - Rendah (perlu ditangani dalam 2 minggu)</option>
                                                <option value="3">3 - Sedang (perlu ditangani dalam 1 minggu)</option>
                                                <option value="4">4 - Tinggi (perlu ditangani dalam 3 hari)</option>
                                                <option value="5">5 - Sangat Tinggi (perlu ditangani segera)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Potensi Penyebaran</label>
                                            <select name="spread_potential" class="form-select" required>
                                                <option value="">Pilih Potensi Penyebaran</option>
                                                <option value="1">1 - Sangat Rendah (terisolasi)</option>
                                                <option value="2">2 - Rendah (lingkup terbatas)</option>
                                                <option value="3">3 - Sedang (dapat menyebar ke komunitas)</option>
                                                <option value="4">4 - Tinggi (berpotensi viral)</option>
                                                <option value="5">5 - Sangat Tinggi (ancaman massal)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prioritas Awal</label>
                                            <select name="priority_level" class="form-select" required>
                                                <option value="">Pilih Prioritas</option>
                                                <option value="low">Rendah</option>
                                                <option value="medium">Sedang</option>
                                                <option value="high">Tinggi</option>
                                                <option value="critical">Kritis</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary me-md-2" onclick="resetForm()">
                                        <i class="fas fa-undo me-1"></i>Reset
                                    </button>
                                    <button type="submit" name="add_case" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Simpan Kasus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel Informasi dan Kasus Terbaru -->
                <div class="col-lg-4">
                    <!-- Info Panel -->
                    <div class="card border-info mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Panduan Input Kasus
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6 class="text-primary">Tips Pengisian:</h6>
                            <ul class="list-unstyled small">
                                <li><i class="fas fa-check text-success me-2"></i>Isi semua field yang wajib (*)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Berikan deskripsi yang jelas dan lengkap</li>
                                <li><i class="fas fa-check text-success me-2"></i>Estimasi kerugian dalam Rupiah</li>
                                <li><i class="fas fa-check text-success me-2"></i>Penilaian kriteria mempengaruhi prioritas</li>
                            </ul>
                            
                            <h6 class="text-primary mt-3">Skala Penilaian:</h6>
                            <div class="small">
                                <span class="badge bg-danger me-1">1</span>Sangat Rendah<br>
                                <span class="badge bg-warning me-1">2</span>Rendah<br>
                                <span class="badge bg-info me-1">3</span>Sedang<br>
                                <span class="badge bg-primary me-1">4</span>Tinggi<br>
                                <span class="badge bg-success me-1">5</span>Sangat Tinggi
                            </div>
                        </div>
                    </div>

                    <!-- Kasus Terbaru -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Kasus Terbaru
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (count($recent_cases) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach (array_slice($recent_cases, 0, 5) as $case): ?>
                                        <div class="list-group-item px-0 py-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 small"><?php echo htmlspecialchars($case['case_number']); ?></h6>
                                                    <p class="mb-1 small text-muted">
                                                        <?php echo strlen($case['case_name']) > 30 ? 
                                                            substr(htmlspecialchars($case['case_name']), 0, 30) . '...' : 
                                                            htmlspecialchars($case['case_name']); ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y', strtotime($case['created_at'])); ?>
                                                    </small>
                                                </div>
                                                <span class="badge bg-<?php 
                                                    echo $case['priority_level'] == 'critical' ? 'danger' : 
                                                        ($case['priority_level'] == 'high' ? 'warning' : 
                                                        ($case['priority_level'] == 'medium' ? 'info' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($case['priority_level']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="results.php" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-list me-1"></i>Lihat Semua Kasus
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">Belum ada kasus yang diinput</p>
                                </div>
                            <?php endif; ?>
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

<script>
// Reset form function
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
        document.getElementById('caseForm').reset();
        // Reset nomor kasus ke nilai default
        document.querySelector('input[name="case_number"]').value = '<?php echo $auto_case_number; ?>';
    }
}

// Auto-generate case number
document.addEventListener('DOMContentLoaded', function() {
    // Set tanggal kejadian default ke hari ini
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="incident_date"]').value = today;
    
    // Format input kerugian dengan separator ribuan
    const lossInput = document.querySelector('input[name="estimated_loss"]');
    lossInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value;
    });
    
    // Validasi form sebelum submit
    document.getElementById('caseForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return false;
        }
        
        // Konfirmasi sebelum menyimpan
        if (!confirm('Apakah Anda yakin data kasus sudah benar dan ingin disimpan?')) {
            e.preventDefault();
            return false;
        }
    });
});

// Auto-suggest case name based on case type
document.querySelector('select[name="case_type"]').addEventListener('change', function() {
    const caseNameInput = document.querySelector('input[name="case_name"]');
    if (!caseNameInput.value) {
        const suggestions = {
            'phishing': 'Kasus Phishing ',
            'hacking': 'Kasus Hacking ',
            'fraud': 'Kasus Penipuan Online ',
            'cyberbullying': 'Kasus Cyberbullying ',
            'identity_theft': 'Kasus Pencurian Identitas ',
            'online_scam': 'Kasus Penipuan Online ',
            'malware': 'Kasus Malware ',
            'other': 'Kasus Kejahatan Online '
        };
        
        if (suggestions[this.value]) {
            caseNameInput.value = suggestions[this.value];
            caseNameInput.focus();
            caseNameInput.setSelectionRange(caseNameInput.value.length, caseNameInput.value.length);
        }
    }
});
</script>

</body>
</html>
