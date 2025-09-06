<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../config/database.php';

$page_title = 'TOPSIS - Technique for Order Preference by Similarity to Ideal Solution';
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
                    <i class="fas fa-calculator me-2 text-success"></i>
                    TOPSIS Analysis
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

            <!-- Check AHP Results First -->
            <?php
            $ahpAvailable = false;
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ahp_results WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $ahpCount = $stmt->fetch()['count'];
                $ahpAvailable = ($ahpCount >= 4);
            } catch (Exception $e) {
                $ahpAvailable = false;
            }
            ?>

            <?php if (!$ahpAvailable): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Bobot Kriteria Belum Tersedia</h5>
                        <p>Untuk melakukan perhitungan TOPSIS, Anda harus melakukan perhitungan AHP terlebih dahulu untuk menentukan bobot kriteria.</p>
                        <a href="ahp.php" class="btn btn-primary">
                            <i class="fas fa-chart-line me-2"></i>Lakukan Perhitungan AHP
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>

            <!-- Input Alternatif -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus-circle me-2"></i>
                                Input Data Kasus
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="topsisForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="caseId" class="form-label">ID Kasus</label>
                                        <input type="text" class="form-control" id="caseId" placeholder="Contoh: KJO-2025-001" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="caseName" class="form-label">Nama Kasus</label>
                                        <input type="text" class="form-control" id="caseName" placeholder="Contoh: Penipuan Online" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="kerugian" class="form-label">Tingkat Kerugian (Rp)</label>
                                        <input type="number" class="form-control" id="kerugian" placeholder="50000000" min="0" required>
                                        <div class="form-text">Masukkan nilai kerugian dalam Rupiah</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dampak" class="form-label">Tingkat Dampak (1-5)</label>
                                        <select class="form-select" id="dampak" required>
                                            <option value="">Pilih</option>
                                            <option value="1">1 - Sangat Rendah</option>
                                            <option value="2">2 - Rendah</option>
                                            <option value="3">3 - Sedang</option>
                                            <option value="4">4 - Tinggi</option>
                                            <option value="5">5 - Sangat Tinggi</option>
                                        </select>
                                        <div class="form-text">Tingkat dampak terhadap korban</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="urgensi" class="form-label">Urgensi (1-5)</label>
                                        <select class="form-select" id="urgensi" required>
                                            <option value="">Pilih</option>
                                            <option value="1">1 - Sangat Rendah</option>
                                            <option value="2">2 - Rendah</option>
                                            <option value="3">3 - Sedang</option>
                                            <option value="4">4 - Tinggi</option>
                                            <option value="5">5 - Sangat Tinggi</option>
                                        </select>
                                        <div class="form-text">Tingkat urgensi penanganan</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="penyebaran" class="form-label">Potensi Penyebaran (1-5)</label>
                                        <select class="form-select" id="penyebaran" required>
                                            <option value="">Pilih</option>
                                            <option value="1">1 - Sangat Rendah</option>
                                            <option value="2">2 - Rendah</option>
                                            <option value="3">3 - Sedang</option>
                                            <option value="4">4 - Tinggi</option>
                                            <option value="5">5 - Sangat Tinggi</option>
                                        </select>
                                        <div class="form-text">Potensi penyebaran ke korban lain</div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Tambah Kasus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Daftar Kasus -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                Daftar Kasus untuk Analisis
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="casesTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID Kasus</th>
                                            <th>Nama Kasus</th>
                                            <th>Kerugian (Rp)</th>
                                            <th>Tingkat Dampak</th>
                                            <th>Urgensi</th>
                                            <th>Penyebaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="casesTableBody">
                                        <!-- Sample data -->
                                        <tr>
                                            <td>KJO-2025-001</td>
                                            <td>Penipuan Online Marketplace</td>
                                            <td>Rp 49,944,304</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>3</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="removeCase(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KJO-2025-002</td>
                                            <td>Investasi Bodong Online</td>
                                            <td>Rp 55,000,000</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>3</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="removeCase(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KJO-2025-003</td>
                                            <td>Phishing Banking</td>
                                            <td>Rp 63,000,000</td>
                                            <td>3</td>
                                            <td>3</td>
                                            <td>2</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="removeCase(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-info btn-lg me-2" onclick="loadRestaurantData()">
                                    <i class="fas fa-utensils me-2"></i>
                                    Muat Data Restoran
                                </button>
                                <button type="button" class="btn btn-primary btn-lg" id="calculateTOPSISBtn" onclick="calculateTOPSIS()">
                                    <i class="fas fa-calculator me-2"></i>
                                    Hitung TOPSIS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOPSIS Results -->
            <div class="row mt-4" id="topsisResults" style="display: none;">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-trophy me-2"></i>
                                Hasil Perhitungan TOPSIS
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped results-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Ranking</th>
                                            <th>ID Kasus</th>
                                            <th>Nama Kasus</th>
                                            <th>Skor TOPSIS</th>
                                            <th>Status Prioritas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topsisResultsBody">
                                        <!-- Results will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <canvas id="priorityChart" width="400" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Interpretasi Hasil:</h6>
                                        <ul class="mb-0">
                                            <li><strong>Skor TOPSIS:</strong> Nilai antara 0-1, semakin tinggi semakin prioritas</li>
                                            <li><strong>Ranking 1:</strong> Kasus dengan prioritas tertinggi</li>
                                            <li><strong>Status Prioritas:</strong> Tingkat urgensi penanganan</li>
                                        </ul>
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

<?php
// Tampilkan hasil TOPSIS jika ada
if (isset($_SESSION['topsis_results'])) {
    $results = $_SESSION['topsis_results'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const results = " . json_encode($results) . ";
            displayTOPSISResults(results);
        });
    </script>";
    unset($_SESSION['topsis_results']);
}
?>

<script>
let casesData = [
    {
        id: 'KJO-2025-001',
        name: 'Penipuan Online Marketplace',
        kerugian: 49944304,
        dampak: 3,
        urgensi: 4,
        penyebaran: 3
    },
    {
        id: 'KJO-2025-002',
        name: 'Investasi Bodong Online',
        kerugian: 55000000,
        dampak: 4,
        urgensi: 5,
        penyebaran: 3
    },
    {
        id: 'KJO-2025-003',
        name: 'Phishing Banking',
        kerugian: 63000000,
        dampak: 3,
        urgensi: 3,
        penyebaran: 2
    }
];

// Add new case
document.getElementById('topsisForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const caseId = document.getElementById('caseId').value;
    const caseName = document.getElementById('caseName').value;
    const kerugian = parseInt(document.getElementById('kerugian').value);
    const dampak = parseInt(document.getElementById('dampak').value);
    const urgensi = parseInt(document.getElementById('urgensi').value);
    const penyebaran = parseInt(document.getElementById('penyebaran').value);

    // Check if case ID already exists
    if (casesData.find(c => c.id === caseId)) {
        alert('ID Kasus sudah ada! Gunakan ID yang berbeda.');
        return;
    }

    // Add to data array
    const newCase = {
        id: caseId,
        name: caseName,
        kerugian: kerugian,
        dampak: dampak,
        urgensi: urgensi,
        penyebaran: penyebaran
    };

    casesData.push(newCase);

    // Add to table
    const tbody = document.getElementById('casesTableBody');
    const newRow = `
        <tr data-case-id="${caseId}">
            <td>${caseId}</td>
            <td>${caseName}</td>
            <td>Rp ${kerugian.toLocaleString('id-ID')}</td>
            <td>${korban}</td>
            <td>${urgensi}</td>
            <td>${penyebaran}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="removeCase(this, '${caseId}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    tbody.innerHTML += newRow;
    this.reset();

    // Show success message
    showNotification('Kasus berhasil ditambahkan!', 'success');
});

// Remove case
function removeCase(button, caseId) {
    if (confirm('Apakah Anda yakin ingin menghapus kasus ini?')) {
        // Remove from data array
        casesData = casesData.filter(c => c.id !== caseId);

        // Remove from table
        button.closest('tr').remove();
        showNotification('Kasus berhasil dihapus!', 'warning');
    }
}

// Load restaurant data function
function loadRestaurantData() {
    if (confirm('Apakah Anda ingin memuat data restoran contoh? Data kasus yang ada akan diganti.')) {
        // Sample restaurant data
        const restaurantData = [
            {
                id: 'RST-001',
                name: 'Warung Makan Padang',
                kerugian: 25000000,
                dampak: 4,
                urgensi: 4,
                penyebaran: 3
            },
            {
                id: 'RST-002',
                name: 'Restoran Cepat Saji',
                kerugian: 45000000,
                dampak: 5,
                urgensi: 5,
                penyebaran: 4
            },
            {
                id: 'RST-003',
                name: 'Kafe Modern',
                kerugian: 18000000,
                dampak: 3,
                urgensi: 3,
                penyebaran: 2
            },
            {
                id: 'RST-004',
                name: 'Restoran Fine Dining',
                kerugian: 75000000,
                dampak: 4,
                urgensi: 4,
                penyebaran: 3
            }
        ];

        // Update cases data
        casesData = restaurantData;

        // Update table
        const tbody = document.getElementById('casesTableBody');
        tbody.innerHTML = '';

        restaurantData.forEach(restaurant => {
            const row = `
                <tr data-case-id="${restaurant.id}">
                    <td>${restaurant.id}</td>
                    <td>${restaurant.name}</td>
                    <td>Rp ${restaurant.kerugian.toLocaleString('id-ID')}</td>
                    <td>${restaurant.dampak}</td>
                    <td>${restaurant.urgensi}</td>
                    <td>${restaurant.penyebaran}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeCase(this, '${restaurant.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        showNotification('Data restoran berhasil dimuat!', 'success');
    }
}

// Calculate TOPSIS
function calculateTOPSIS() {
    if (casesData.length < 2) {
        alert('Minimal diperlukan 2 kasus untuk perhitungan TOPSIS!');
        return;
    }

    // Show loading
    const btn = document.getElementById('calculateTOPSISBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung...';
    btn.disabled = true;

    // Send data to backend using fetch
    fetch('../process_topsis.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'alternatives=' + encodeURIComponent(JSON.stringify(casesData))
    })
    .then(response => response.text())
    .then(data => {
        // Check if response contains redirect (success)
        if (data.includes('Location:') || data.trim() === '') {
            // Reload page to show results
            window.location.reload();
        } else {
            // Handle error
            console.error('Error:', data);
            alert('Terjadi kesalahan dalam perhitungan TOPSIS');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan dalam perhitungan TOPSIS');
    })
    .finally(() => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Display TOPSIS results
function displayTOPSISResults(results) {
    const tbody = document.getElementById('topsisResultsBody');
    tbody.innerHTML = '';

    results.forEach(result => {
        const priorityClass = result.rank === 1 ? 'bg-danger' : result.rank === 2 ? 'bg-warning' : 'bg-info';
        const priorityText = result.rank === 1 ? 'Sangat Tinggi' : result.rank === 2 ? 'Tinggi' : 'Sedang';

        const row = `
            <tr>
                <td><span class="badge ${priorityClass}">${result.rank}</span></td>
                <td>${result.alternative.id}</td>
                <td>${result.alternative.name}</td>
                <td><strong>${result.coefficient.toFixed(4)}</strong></td>
                <td><span class="badge ${priorityClass}">${priorityText}</span></td>
            </tr>
        `;
        tbody.innerHTML += row;
    });

    // Show results section
    document.getElementById('topsisResults').style.display = 'block';
    document.getElementById('topsisResults').scrollIntoView({ behavior: 'smooth' });

    showNotification('Perhitungan TOPSIS berhasil!', 'success');
}

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

// Format currency input
document.getElementById('kerugian')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value) {
        e.target.value = value;
    }
});
</script>

<?php include '../includes/footer.php'; ?>
