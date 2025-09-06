<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$page_title = 'AHP - Analytic Hierarchy Process';
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
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Analytic Hierarchy Process (AHP)
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download me-1"></i>Export
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

            <!-- AHP Configuration -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Konfigurasi Kriteria AHP
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Tentukan bobot kepentingan antar kriteria menggunakan skala perbandingan berpasangan Saaty (1-9).
                            </p>

                            <form id="ahpForm" method="POST" action="../process_ahp.php">
                                <!-- Toggle untuk Mode Tampilan -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group" role="group" aria-label="Mode Tampilan">
                                            <input type="radio" class="btn-check" name="displayMode" id="modeEdit" value="edit" checked>
                                            <label class="btn btn-outline-primary" for="modeEdit">
                                                <i class="fas fa-edit me-2"></i>Mode Edit
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="displayMode" id="modeResearch" value="research">
                                            <label class="btn btn-outline-success" for="modeResearch">
                                                <i class="fas fa-book me-2"></i>Hasil Penelitian
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mode Edit (Default) -->
                                <div id="editMode" class="mode-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th>Tingkat Kerugian</th>
                                                    <th>Tingkat Dampak</th>
                                                    <th>Urgensi Penanganan</th>
                                                    <th>Ketersediaan Sumber Daya</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold">Tingkat Kerugian</td>
                                                    <td class="bg-light">1</td>
                                                    <td>
                                                        <select class="form-select ahp-input" name="c1_c2" required>
                                                            <option value="">Pilih</option>
                                                            <option value="1">1 - Sama penting</option>
                                                            <option value="3">3 - Sedikit lebih penting</option>
                                                            <option value="4" selected>4 - Lebih penting</option>
                                                            <option value="5">5 - Lebih penting</option>
                                                            <option value="7">7 - Sangat lebih penting</option>
                                                            <option value="9">9 - Mutlak lebih penting</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-select ahp-input" name="c1_c3" required>
                                                            <option value="">Pilih</option>
                                                            <option value="1">1 - Sama penting</option>
                                                            <option value="3">3 - Sedikit lebih penting</option>
                                                            <option value="5" selected>5 - Lebih penting</option>
                                                            <option value="7">7 - Sangat lebih penting</option>
                                                            <option value="9">9 - Mutlak lebih penting</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-select ahp-input" name="c1_c4" required>
                                                            <option value="">Pilih</option>
                                                            <option value="1">1 - Sama penting</option>
                                                            <option value="3">3 - Sedikit lebih penting</option>
                                                            <option value="5">5 - Lebih penting</option>
                                                            <option value="6" selected>6 - Jauh lebih penting</option>
                                                            <option value="7">7 - Sangat lebih penting</option>
                                                            <option value="9">9 - Mutlak lebih penting</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Tingkat Dampak</td>
                                                    <td class="bg-light reciprocal-c1c2">1/4</td>
                                                    <td class="bg-light">1</td>
                                                    <td>
                                                        <select class="form-select ahp-input" name="c2_c3" required>
                                                            <option value="">Pilih</option>
                                                            <option value="1">1 - Sama penting</option>
                                                            <option value="3" selected>3 - Sedikit lebih penting</option>
                                                            <option value="5">5 - Lebih penting</option>
                                                            <option value="7">7 - Sangat lebih penting</option>
                                                            <option value="9">9 - Mutlak lebih penting</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-select ahp-input" name="c2_c4" required>
                                                            <option value="">Pilih</option>
                                                            <option value="1">1 - Sama penting</option>
                                                            <option value="3">3 - Sedikit lebih penting</option>
                                                            <option value="4" selected>4 - Lebih penting</option>
                                                            <option value="5">5 - Lebih penting</option>
                                                            <option value="7">7 - Sangat lebih penting</option>
                                                            <option value="9">9 - Mutlak lebih penting</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Urgensi Penanganan</td>
                                                    <td class="bg-light reciprocal-c1c3">1/5</td>
                                                    <td class="bg-light reciprocal-c2c3">1/3</td>
                                                    <td class="bg-light">1</td>
                                                    <td>
                                                        <select class="form-select ahp-input" name="c3_c4" required>
                                                            <option value="">Pilih</option>
                                                            <option value="1">1 - Sama penting</option>
                                                            <option value="3" selected>3 - Sedikit lebih penting</option>
                                                            <option value="5">5 - Lebih penting</option>
                                                            <option value="7">7 - Sangat lebih penting</option>
                                                            <option value="9">9 - Mutlak lebih penting</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Ketersediaan Sumber Daya</td>
                                                    <td class="bg-light reciprocal-c1c4">1/6</td>
                                                    <td class="bg-light reciprocal-c2c4">1/4</td>
                                                    <td class="bg-light reciprocal-c3c4">1/3</td>
                                                    <td class="bg-light">1</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Mode Hasil Penelitian -->
                                <div id="researchMode" class="mode-content" style="display: none;">
                                    <!-- Matriks Perbandingan Kriteria berdasarkan Tabel IV.6 dari Skripsi -->
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Matriks Perbandingan Kriteria (Hasil Rata-rata Responden)</strong><br>
                                        <small>Berdasarkan Tabel IV.6 dari penelitian skripsi - hasil wawancara dengan aparat Polsek Saribudolok</small>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th>Tingkat Kerugian (C1)</th>
                                                    <th>Tingkat Dampak (C2)</th>
                                                    <th>Urgensi Penanganan (C3)</th>
                                                    <th>Ketersediaan Sumber Daya (C4)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold bg-primary text-white">Tingkat Kerugian (C1)</td>
                                                    <td class="bg-light text-center fw-bold">1</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">4</span>
                                                        <br><small class="text-muted">Lebih penting</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">5</span>
                                                        <br><small class="text-muted">Lebih penting</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">6</span>
                                                        <br><small class="text-muted">Jauh lebih penting</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold bg-info text-white">Tingkat Dampak (C2)</td>
                                                    <td class="bg-light text-center">
                                                        <span class="badge bg-secondary fs-6">1/4</span>
                                                        <br><small class="text-muted">0.25</small>
                                                    </td>
                                                    <td class="bg-light text-center fw-bold">1</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">3</span>
                                                        <br><small class="text-muted">Sedikit lebih penting</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">4</span>
                                                        <br><small class="text-muted">Lebih penting</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold bg-warning text-dark">Urgensi Penanganan (C3)</td>
                                                    <td class="bg-light text-center">
                                                        <span class="badge bg-secondary fs-6">1/5</span>
                                                        <br><small class="text-muted">0.20</small>
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        <span class="badge bg-secondary fs-6">1/3</span>
                                                        <br><small class="text-muted">0.33</small>
                                                    </td>
                                                    <td class="bg-light text-center fw-bold">1</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">3</span>
                                                        <br><small class="text-muted">Sedikit lebih penting</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold bg-danger text-white">Ketersediaan Sumber Daya (C4)</td>
                                                    <td class="bg-light text-center">
                                                        <span class="badge bg-secondary fs-6">1/6</span>
                                                        <br><small class="text-muted">0.17</small>
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        <span class="badge bg-secondary fs-6">1/4</span>
                                                        <br><small class="text-muted">0.25</small>
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        <span class="badge bg-secondary fs-6">1/3</span>
                                                        <br><small class="text-muted">0.33</small>
                                                    </td>
                                                    <td class="bg-light text-center fw-bold">1</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Informasi Bobot Kriteria Hasil Penelitian -->
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="card border-success">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0"><i class="fas fa-weight-hanging me-2"></i>Bobot Kriteria (Hasil Penelitian)</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <strong>C1 - Tingkat Kerugian:</strong><br>
                                                            <span class="badge bg-primary fs-6">0.5748 (57.48%)</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>C2 - Tingkat Dampak:</strong><br>
                                                            <span class="badge bg-info fs-6">0.2352 (23.52%)</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <strong>C3 - Urgensi Penanganan:</strong><br>
                                                            <span class="badge bg-warning fs-6">0.1262 (12.62%)</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>C4 - Ketersediaan Sumber Daya:</strong><br>
                                                            <span class="badge bg-danger fs-6">0.0638 (6.38%)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-info">
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Konsistensi Matriks</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Consistency Ratio (CR):</strong> <span class="badge bg-success">0.0874</span></p>
                                                    <p class="mb-0"><small class="text-muted">CR < 0.10 = Matriks Konsisten ✓</small></p>
                                                    <hr>
                                                    <p class="mb-0"><strong>λ max:</strong> 4.236</p>
                                                    <p class="mb-0"><strong>CI:</strong> 0.0787</p>
                                                    <p class="mb-0"><strong>RI:</strong> 0.90 (n=4)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <button type="submit" id="submitAHP" class="btn btn-primary btn-lg">
                                            <i class="fas fa-calculator me-2"></i>
                                            Hitung Bobot AHP
                                        </button>
                                        <small class="text-muted d-block mt-1">Hitung dan simpan bobot kriteria</small>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-success btn-lg" id="saveAHPBtn" onclick="saveAHPResults()" style="display: none;">
                                            <i class="fas fa-save me-2"></i>
                                            Simpan Hasil AHP
                                        </button>
                                        <small class="text-muted d-block mt-1">Simpan hasil perhitungan ke database</small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo me-2"></i>
                                            Reset
                                        </button>
                                        <small class="text-muted d-block mt-1">Reset form perbandingan</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AHP Results -->
            <div class="row mt-4" id="ahpResults" style="display: none;">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Hasil Perhitungan AHP
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Bobot Kriteria:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th>Bobot</th>
                                                    <th>Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody id="criteriaWeights">
                                                <!-- Results will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Consistency Ratio (CR):</h6>
                                    <div class="alert alert-info">
                                        <strong>CR = <span id="consistencyRatio">0.00</span></strong>
                                        <br>
                                        <small class="text-muted">
                                            CR ≤ 0.10 = Konsisten<br>
                                            CR > 0.10 = Tidak Konsisten
                                        </small>
                                    </div>
                                    <canvas id="criteriaChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Panduan Skala Perbandingan AHP
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nilai</th>
                                            <th>Definisi</th>
                                            <th>Penjelasan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="badge bg-primary">1</span></td>
                                            <td>Sama penting</td>
                                            <td>Kedua kriteria memiliki kepentingan yang sama</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-primary">3</span></td>
                                            <td>Sedikit lebih penting</td>
                                            <td>Satu kriteria sedikit lebih penting dari yang lain</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-primary">5</span></td>
                                            <td>Lebih penting</td>
                                            <td>Satu kriteria jelas lebih penting dari yang lain</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-primary">7</span></td>
                                            <td>Sangat lebih penting</td>
                                            <td>Satu kriteria sangat jelas lebih penting dari yang lain</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-primary">9</span></td>
                                            <td>Mutlak lebih penting</td>
                                            <td>Satu kriteria mutlak lebih penting dari yang lain</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Tampilkan hasil AHP jika ada atau jika sudah ada hasil di database
$showResults = false;
if (isset($_SESSION['success']) && strpos($_SESSION['success'], 'AHP') !== false) {
    $showResults = true;
} else {
    // Cek apakah sudah ada hasil AHP di database
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ahp_results WHERE created_by = ?");
        $stmt->execute([$_SESSION['user_id']]);
        if ($stmt->fetch()['count'] > 0) {
            $showResults = true;
        }
    } catch (Exception $e) {
        // Ignore
    }
}

if ($showResults) {
    // Ambil hasil AHP terbaru
    try {
        $stmt = $pdo->prepare("
            SELECT c.name as criteria_name, ar.weight, ar.consistency_ratio
            FROM ahp_results ar
            JOIN criteria c ON ar.criteria_id = c.id
            WHERE ar.created_by = ?
            ORDER BY ar.created_at DESC
            LIMIT 4
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $ahpResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    if (count($ahpResults) > 0) {
        $cr = $ahpResults[0]['consistency_ratio'];
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('ahpResults').style.display = 'block';

                    const weights = " . json_encode($ahpResults) . ";
                    const tbody = document.getElementById('criteriaWeights');
                    tbody.innerHTML = '';

                    weights.forEach(item => {
                        const percentage = (item.weight * 100).toFixed(2) + '%';
                        const row = `
                            <tr>
                                <td>\${item.criteria_name}</td>
                                <td>\${parseFloat(item.weight).toFixed(4)}</td>
                                <td><span class=\"badge bg-primary\">\${percentage}</span></td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });

                    document.getElementById('consistencyRatio').textContent = '" . number_format($cr, 4) . "';

                    // Show save button for existing results
                    const saveBtn = document.getElementById('saveAHPBtn');
                    if (saveBtn) {
                        saveBtn.style.display = 'inline-block';
                        saveBtn.innerHTML = '<i class=\"fas fa-save me-2\"></i>Simpan Hasil AHP';
                    }

                    document.getElementById('ahpResults').scrollIntoView({ behavior: 'smooth' });
                });
            </script>";
        }
    } catch (Exception $e) {
        // Ignore database errors for display
    }
}
?>

<script>
function resetForm() {
    document.getElementById('ahpForm').reset();
    document.getElementById('ahpResults').style.display = 'none';
    // Reset to edit mode
    document.getElementById('modeEdit').checked = true;
    toggleDisplayMode();
}

// Save AHP results (for future use if needed)
function saveAHPResults() {
    alert('Hasil AHP sudah otomatis disimpan saat perhitungan selesai!');
}

// Toggle between edit mode and research mode
function toggleDisplayMode() {
    const editMode = document.getElementById('editMode');
    const researchMode = document.getElementById('researchMode');
    const isEditMode = document.getElementById('modeEdit').checked;
    
    if (isEditMode) {
        editMode.style.display = 'block';
        researchMode.style.display = 'none';
    } else {
        editMode.style.display = 'none';
        researchMode.style.display = 'block';
    }
}

// Event listeners for mode toggle
document.getElementById('modeEdit').addEventListener('change', toggleDisplayMode);
document.getElementById('modeResearch').addEventListener('change', toggleDisplayMode);

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Form validation
document.getElementById('ahpForm').addEventListener('submit', function(e) {
    // Only validate if in edit mode
    if (document.getElementById('modeEdit').checked) {
        const selects = this.querySelectorAll('#editMode select[required]');
        let allFilled = true;

        selects.forEach(select => {
            if (!select.value) {
                allFilled = false;
                select.classList.add('is-invalid');
            } else {
                select.classList.remove('is-invalid');
            }
        });

        if (!allFilled) {
            e.preventDefault();
            alert('Mohon lengkapi semua perbandingan kriteria!');
            return false;
        }
    }

    // Show loading
    const submitBtn = document.getElementById('submitAHP');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung...';
    submitBtn.disabled = true;

    // Allow form submission to backend
    return true;
});

// Auto-fill reciprocal values display
document.querySelectorAll('.ahp-input').forEach(select => {
    select.addEventListener('change', function() {
        updateReciprocalDisplay();
    });
});

function updateReciprocalDisplay() {
    // Update display untuk nilai reciprocal (1/x) hanya di edit mode
    const c1c2 = document.querySelector('select[name="c1_c2"]').value;
    const c1c3 = document.querySelector('select[name="c1_c3"]').value;
    const c1c4 = document.querySelector('select[name="c1_c4"]').value;
    const c2c3 = document.querySelector('select[name="c2_c3"]').value;
    const c2c4 = document.querySelector('select[name="c2_c4"]').value;
    const c3c4 = document.querySelector('select[name="c3_c4"]').value;

    // Update tampilan 1/x di tabel edit mode
    const reciprocalC1C2 = document.querySelector('.reciprocal-c1c2');
    const reciprocalC1C3 = document.querySelector('.reciprocal-c1c3');
    const reciprocalC1C4 = document.querySelector('.reciprocal-c1c4');
    const reciprocalC2C3 = document.querySelector('.reciprocal-c2c3');
    const reciprocalC2C4 = document.querySelector('.reciprocal-c2c4');
    const reciprocalC3C4 = document.querySelector('.reciprocal-c3c4');

    if (c1c2 && reciprocalC1C2) {
        reciprocalC1C2.textContent = c1c2 == 1 ? '1' : '1/' + c1c2;
    }
    if (c1c3 && reciprocalC1C3) {
        reciprocalC1C3.textContent = c1c3 == 1 ? '1' : '1/' + c1c3;
    }
    if (c1c4 && reciprocalC1C4) {
        reciprocalC1C4.textContent = c1c4 == 1 ? '1' : '1/' + c1c4;
    }
    if (c2c3 && reciprocalC2C3) {
        reciprocalC2C3.textContent = c2c3 == 1 ? '1' : '1/' + c2c3;
    }
    if (c2c4 && reciprocalC2C4) {
        reciprocalC2C4.textContent = c2c4 == 1 ? '1' : '1/' + c2c4;
    }
    if (c3c4 && reciprocalC3C4) {
        reciprocalC3C4.textContent = c3c4 == 1 ? '1' : '1/' + c3c4;
    }
}

// Initialize reciprocal display on page load
document.addEventListener('DOMContentLoaded', function() {
    updateReciprocalDisplay();
});
</script>

<?php include '../includes/footer.php'; ?>
