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
            $ahpSource = 'default';
            try {
                // Check if user has their own AHP results
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ahp_results WHERE created_by = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $userAhpCount = $stmt->fetch()['count'];

                if ($userAhpCount >= 4) {
                    $ahpAvailable = true;
                    $ahpSource = 'user';
                } else {
                    // Check if admin has AHP results
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) as count
                        FROM ahp_results ar
                        JOIN users u ON ar.created_by = u.id
                        WHERE u.role = 'admin'
                    ");
                    $stmt->execute();
                    $adminAhpCount = $stmt->fetch()['count'];

                    if ($adminAhpCount >= 4) {
                        $ahpAvailable = true;
                        $ahpSource = 'admin';
                    } else {
                        $ahpAvailable = true; // Default weights available
                        $ahpSource = 'default';
                    }
                }
            } catch (Exception $e) {
                $ahpAvailable = true; // Default weights always available
                $ahpSource = 'default';
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

            <!-- AHP Status Info -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Status Bobot Kriteria:</h6>
                        <p class="mb-0">
                            <?php if ($ahpSource === 'user'): ?>
                                <i class="fas fa-user-check text-success me-2"></i>
                                Menggunakan bobot AHP hasil perhitungan Anda sendiri
                            <?php elseif ($ahpSource === 'admin'): ?>
                                <i class="fas fa-user-shield text-primary me-2"></i>
                                Menggunakan bobot AHP dari Administrator
                            <?php else: ?>
                                <i class="fas fa-calculator text-warning me-2"></i>
                                Menggunakan bobot default berdasarkan skripsi (<?php echo $_SESSION['role'] === 'admin' ? 'Anda dapat melakukan AHP untuk bobot kustom' : 'Admin dapat melakukan AHP untuk bobot kustom'; ?>)
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

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
                                        <label for="korban" class="form-label">Jumlah Korban (1-5)</label>
                                        <select class="form-select" id="korban" required>
                                            <option value="">Pilih</option>
                                            <option value="1">1 - Sangat Sedikit</option>
                                            <option value="2">2 - Sedikit</option>
                                            <option value="3">3 - Sedang</option>
                                            <option value="4">4 - Banyak</option>
                                            <option value="5">5 - Sangat Banyak</option>
                                        </select>
                                        <div class="form-text">Jumlah korban yang terdampak</div>
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
                                            <th>Jumlah Korban</th>
                                            <th>Urgensi</th>
                                            <th>Penyebaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="casesTableBody">
                                        <!-- Data will be loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Status Data:</strong> <span id="casesStatus">0 kasus siap untuk disimpan</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success btn-lg" id="saveCasesBtn" onclick="saveCases()">
                                            <i class="fas fa-save me-2"></i>
                                            Simpan Data Kasus
                                        </button>
                                        <small class="text-muted d-block mt-1">Simpan data kasus ke database tanpa perhitungan</small>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary btn-lg" id="calculateTOPSISBtn" onclick="calculateTOPSIS()">
                                            <i class="fas fa-calculator me-2"></i>
                                            Hitung TOPSIS
                                        </button>
                                        <small class="text-muted d-block mt-1">Jalankan perhitungan TOPSIS dengan data tersimpan</small>
                                    </div>
                                </div>
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
let casesData = [];

// Load cases from database
function loadCasesFromDatabase() {
    fetch('../save_topsis_cases.php?action=load')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error loading cases:', data.error);
                // Use default sample data if database load fails
                casesData = [
                    {
                        id: 'KJO-2025-001',
                        name: 'Penipuan Online Marketplace',
                        kerugian: 49944304,
                        korban: 3,
                        urgensi: 4,
                        penyebaran: 3
                    },
                    {
                        id: 'KJO-2025-002',
                        name: 'Investasi Bodong Online',
                        kerugian: 55000000,
                        korban: 4,
                        urgensi: 5,
                        penyebaran: 3
                    },
                    {
                        id: 'KJO-2025-003',
                        name: 'Phishing Banking',
                        kerugian: 63000000,
                        korban: 3,
                        urgensi: 3,
                        penyebaran: 2
                    }
                ];
            } else {
                casesData = data.length > 0 ? data : [
                    {
                        id: 'KJO-2025-001',
                        name: 'Penipuan Online Marketplace',
                        kerugian: 49944304,
                        korban: 3,
                        urgensi: 4,
                        penyebaran: 3
                    },
                    {
                        id: 'KJO-2025-002',
                        name: 'Investasi Bodong Online',
                        kerugian: 55000000,
                        korban: 4,
                        urgensi: 5,
                        penyebaran: 3
                    },
                    {
                        id: 'KJO-2025-003',
                        name: 'Phishing Banking',
                        kerugian: 63000000,
                        korban: 3,
                        urgensi: 3,
                        penyebaran: 2
                    }
                ];
            }
            initializeTable();
        })
        .catch(error => {
            console.error('Error loading cases:', error);
            // Use default sample data if fetch fails
            casesData = [
                {
                    id: 'KJO-2025-001',
                    name: 'Penipuan Online Marketplace',
                    kerugian: 49944304,
                    korban: 3,
                    urgensi: 4,
                    penyebaran: 3
                },
                {
                    id: 'KJO-2025-002',
                    name: 'Investasi Bodong Online',
                    kerugian: 55000000,
                    korban: 4,
                    urgensi: 5,
                    penyebaran: 3
                },
                {
                    id: 'KJO-2025-003',
                    name: 'Phishing Banking',
                    kerugian: 63000000,
                    korban: 3,
                    urgensi: 3,
                    penyebaran: 2
                }
            ];
            initializeTable();
        });
}



// Remove case
function removeCase(button, caseId) {
    if (confirm('Apakah Anda yakin ingin menghapus kasus ini?')) {
        // Remove from data array
        casesData = casesData.filter(c => c.id !== caseId);

        // Save to database
        saveCasesToDatabase();

        // Remove from table
        button.closest('tr').remove();

        // Update status
        updateCasesStatus();

        showNotification('Kasus berhasil dihapus!', 'warning');
    }
}

// Save cases to database
function saveCasesToDatabase() {
    fetch('save_topsis_cases.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cases=' + encodeURIComponent(JSON.stringify(casesData))
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            console.log('Cases saved to database successfully');
        } else {
            console.error('Error saving cases:', data);
        }
    })
    .catch(error => {
        console.error('Error saving cases:', error);
    });
}

// Load cases from session
function loadCasesFromSession() {
    // Cases are loaded from PHP session at page load
    // This function can be used for dynamic loading if needed
}

// Edit case
function editCase(caseId) {
    const caseData = casesData.find(c => c.id === caseId);
    if (!caseData) return;

    // Populate form with case data
    document.getElementById('caseId').value = caseData.id;
    document.getElementById('caseName').value = caseData.name;
    document.getElementById('kerugian').value = caseData.kerugian;
    document.getElementById('korban').value = caseData.korban;
    document.getElementById('urgensi').value = caseData.urgensi;
    document.getElementById('penyebaran').value = caseData.penyebaran;

    // Change form submit behavior to update
    const form = document.getElementById('topsisForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Kasus';

    // Remove existing event listener and add update listener
    form.removeEventListener('submit', addCaseHandler);
    form.addEventListener('submit', function updateHandler(e) {
        e.preventDefault();

        const updatedCase = {
            id: document.getElementById('caseId').value,
            name: document.getElementById('caseName').value,
            kerugian: parseInt(document.getElementById('kerugian').value),
            korban: parseInt(document.getElementById('korban').value),
            urgensi: parseInt(document.getElementById('urgensi').value),
            penyebaran: parseInt(document.getElementById('penyebaran').value)
        };

        // Update in data array
        const index = casesData.findIndex(c => c.id === caseId);
        if (index !== -1) {
            casesData[index] = updatedCase;
        }

        // Save to database
        saveCasesToDatabase();

        // Update table row
        const row = document.querySelector(`tr[data-case-id="${caseId}"]`);
        if (row) {
            row.cells[0].textContent = updatedCase.id;
            row.cells[1].textContent = updatedCase.name;
            row.cells[2].textContent = 'Rp ' + updatedCase.kerugian.toLocaleString('id-ID');
            row.cells[3].textContent = updatedCase.korban;
            row.cells[4].textContent = updatedCase.urgensi;
            row.cells[5].textContent = updatedCase.penyebaran;
            row.setAttribute('data-case-id', updatedCase.id);
        }

        // Update status
        updateCasesStatus();

        // Reset form
        form.reset();
        submitBtn.innerHTML = originalText;

        // Remove update handler and restore add handler
        form.removeEventListener('submit', updateHandler);
        form.addEventListener('submit', addCaseHandler);

        showNotification('Kasus berhasil diupdate!', 'success');
    });
}

// Store the original add handler
const addCaseHandler = function(e) {
    e.preventDefault();

    const caseId = document.getElementById('caseId').value;
    const caseName = document.getElementById('caseName').value;
    const kerugian = parseInt(document.getElementById('kerugian').value);
    const korban = parseInt(document.getElementById('korban').value);
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
        korban: korban,
        urgensi: urgensi,
        penyebaran: penyebaran
    };

    casesData.push(newCase);

    // Save to database
    saveCasesToDatabase();

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
                <button class="btn btn-sm btn-warning me-1" onclick="editCase('${caseId}')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="removeCase(this, '${caseId}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    tbody.innerHTML += newRow;
    this.reset();

    // Update status
    updateCasesStatus();

    // Show success message
    showNotification('Kasus berhasil ditambahkan!', 'success');
};



// Save cases to database
function saveCases() {
    if (casesData.length === 0) {
        alert('Tidak ada data kasus untuk disimpan!');
        return;
    }

    // Show loading
    const btn = document.getElementById('saveCasesBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    btn.disabled = true;

    // Send cases data to save_topsis_cases.php
    console.log('Sending cases data:', casesData);
    fetch('../save_topsis_cases.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cases=' + encodeURIComponent(JSON.stringify(casesData))
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data === 'success') {
            showNotification('Data kasus berhasil disimpan ke database!', 'success');
            // Update save button to show success state
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Tersimpan';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Data Kasus';
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success');
            }, 3000);
        } else {
            console.error('Error saving cases:', data);
            showNotification('Gagal menyimpan data kasus: ' + data, 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving cases:', error);
        showNotification('Terjadi kesalahan saat menyimpan data!', 'danger');
    })
    .finally(() => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
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

    // Send request to backend (no need to send data since it's loaded from database)
    fetch('../process_topsis.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: '' // Empty body since data comes from database
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

// Update cases status display
function updateCasesStatus() {
    const statusElement = document.getElementById('casesStatus');
    const count = casesData.length;
    if (count === 0) {
        statusElement.textContent = '0 kasus siap untuk disimpan';
        statusElement.className = 'text-muted';
    } else if (count === 1) {
        statusElement.textContent = '1 kasus siap untuk disimpan (minimal 2 kasus untuk TOPSIS)';
        statusElement.className = 'text-warning';
    } else {
        statusElement.textContent = `${count} kasus siap untuk disimpan dan dihitung`;
        statusElement.className = 'text-success';
    }
}

// Initialize table with cases data
function initializeTable() {
    const tbody = document.getElementById('casesTableBody');
    tbody.innerHTML = '';

    casesData.forEach(caseItem => {
        const row = `
            <tr data-case-id="${caseItem.id}">
                <td>${caseItem.id}</td>
                <td>${caseItem.name}</td>
                <td>Rp ${caseItem.kerugian.toLocaleString('id-ID')}</td>
                <td>${caseItem.korban}</td>
                <td>${caseItem.urgensi}</td>
                <td>${caseItem.penyebaran}</td>
                <td>
                    <button class="btn btn-sm btn-warning me-1" onclick="editCase('${caseItem.id}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="removeCase(this, '${caseItem.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });

    updateCasesStatus();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCasesFromDatabase();

    const form = document.getElementById('topsisForm');
    if (form) {
        form.addEventListener('submit', addCaseHandler);
    }
});

// Format currency input
document.getElementById('kerugian')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value) {
        e.target.value = value;
    }
});
</script>

<?php include '../includes/footer.php'; ?>
