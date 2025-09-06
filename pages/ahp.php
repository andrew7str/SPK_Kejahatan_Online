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
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th>Tingkat Kerugian</th>
                                                <th>Jumlah Korban</th>
                                                <th>Urgensi</th>
                                                <th>Potensi Penyebaran</th>
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
                                                        <option value="5">5 - Lebih penting</option>
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
                                                        <option value="7">7 - Sangat lebih penting</option>
                                                        <option value="9">9 - Mutlak lebih penting</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Jumlah Korban</td>
                                                <td class="bg-light">1/x</td>
                                                <td class="bg-light">1</td>
                                                <td>
                                                    <select class="form-select ahp-input" name="c2_c3" required>
                                                        <option value="">Pilih</option>
                                                        <option value="1">1 - Sama penting</option>
                                                        <option value="3">3 - Sedikit lebih penting</option>
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
                                                        <option value="5">5 - Lebih penting</option>
                                                        <option value="7">7 - Sangat lebih penting</option>
                                                        <option value="9">9 - Mutlak lebih penting</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Urgensi</td>
                                                <td class="bg-light">1/x</td>
                                                <td class="bg-light">1/x</td>
                                                <td class="bg-light">1</td>
                                                <td>
                                                    <select class="form-select ahp-input" name="c3_c4" required>
                                                        <option value="">Pilih</option>
                                                        <option value="1">1 - Sama penting</option>
                                                        <option value="3">3 - Sedikit lebih penting</option>
                                                        <option value="5">5 - Lebih penting</option>
                                                        <option value="7">7 - Sangat lebih penting</option>
                                                        <option value="9">9 - Mutlak lebih penting</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Potensi Penyebaran</td>
                                                <td class="bg-light">1/x</td>
                                                <td class="bg-light">1/x</td>
                                                <td class="bg-light">1/x</td>
                                                <td class="bg-light">1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <button type="submit" id="submitAHP" class="btn btn-primary btn-lg">
                                            <i class="fas fa-calculator me-2"></i>
                                            Hitung Bobot AHP
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo me-2"></i>
                                            Reset
                                        </button>
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
// Tampilkan hasil AHP jika ada
if (isset($_SESSION['success']) && strpos($_SESSION['success'], 'AHP') !== false) {
    // Ambil hasil AHP terbaru
    try {
        $stmt = $pdo->prepare("
            SELECT criteria_name, weight, consistency_ratio 
            FROM ahp_results 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
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
                    
                    const crElement = document.getElementById('consistencyRatio').parentElement;
                    if (" . ($cr <= 0.10 ? 'true' : 'false') . ") {
                        crElement.className = 'alert alert-success';
                        crElement.innerHTML = '<strong>CR = " . number_format($cr, 4) . "</strong><br><small class=\"text-muted\">Matriks perbandingan KONSISTEN ✓</small>';
                    } else {
                        crElement.className = 'alert alert-danger';
                        crElement.innerHTML = '<strong>CR = " . number_format($cr, 4) . "</strong><br><small class=\"text-muted\">Matriks perbandingan TIDAK KONSISTEN ✗<br>Silakan perbaiki penilaian perbandingan</small>';
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
}

// Form validation
document.getElementById('ahpForm').addEventListener('submit', function(e) {
    const selects = this.querySelectorAll('select[required]');
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
    // Update display untuk nilai reciprocal (1/x)
    const c1c2 = document.querySelector('select[name="c1_c2"]').value;
    const c1c3 = document.querySelector('select[name="c1_c3"]').value;
    const c1c4 = document.querySelector('select[name="c1_c4"]').value;
    const c2c3 = document.querySelector('select[name="c2_c3"]').value;
    const c2c4 = document.querySelector('select[name="c2_c4"]').value;
    const c3c4 = document.querySelector('select[name="c3_c4"]').value;
    
    // Update tampilan 1/x di tabel
    const reciprocalCells = document.querySelectorAll('.bg-light');
    if (c1c2) reciprocalCells[1].textContent = c1c2 == 1 ? '1' : '1/' + c1c2;
    if (c1c3) reciprocalCells[2].textContent = c1c3 == 1 ? '1' : '1/' + c1c3;
    if (c1c4) reciprocalCells[3].textContent = c1c4 == 1 ? '1' : '1/' + c1c4;
    if (c2c3) reciprocalCells[5].textContent = c2c3 == 1 ? '1' : '1/' + c2c3;
    if (c2c4) reciprocalCells[6].textContent = c2c4 == 1 ? '1' : '1/' + c2c4;
    if (c3c4) reciprocalCells[8].textContent = c3c4 == 1 ? '1' : '1/' + c3c4;
}
</script>

<?php include '../includes/footer.php'; ?>
