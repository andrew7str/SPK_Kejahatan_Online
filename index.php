<?php
session_start();
$page_title = 'Beranda - Sistem Pendukung Keputusan Kejahatan Online';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>Sistem Pendukung Keputusan</h1>
                    <h2 class="h3 mb-4">Prioritas Penanganan Kejahatan Online</h2>
                    <p class="lead">
                        Menggunakan metode AHP (Analytic Hierarchy Process) dan TOPSIS 
                        (Technique for Order Preference by Similarity to Ideal Solution) 
                        untuk menentukan prioritas penanganan kasus kejahatan online secara objektif dan terukur.
                    </p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="hero-buttons">
                        <button type="button" class="btn btn-primary btn-lg me-3" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt me-2"></i>Mulai Sekarang
                        </button>
                        <button type="button" class="btn btn-outline-light btn-lg" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="hero-buttons">
                        <a href="pages/dashboard.php" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="pages/ahp.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-calculator me-2"></i>Mulai Analisis
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alert Messages -->
<?php if (isset($_GET['message']) && $_GET['message'] == 'logout_success'): ?>
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>Logout berhasil!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['error']); endif; ?>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-12">
                <h2 class="display-5 fw-bold">Fitur Utama Sistem</h2>
                <p class="lead text-muted">Solusi komprehensif untuk penanganan kasus kejahatan online</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-chart-line fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Metode AHP</h5>
                        <p class="card-text">
                            Analytic Hierarchy Process untuk menentukan bobot kriteria 
                            secara sistematis dan objektif berdasarkan perbandingan berpasangan.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-sort-amount-down fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Metode TOPSIS</h5>
                        <p class="card-text">
                            Technique for Order Preference by Similarity to Ideal Solution 
                            untuk meranking alternatif berdasarkan kedekatan dengan solusi ideal.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-shield-alt fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Prioritas Kasus</h5>
                        <p class="card-text">
                            Sistem menghasilkan prioritas penanganan kasus kejahatan online 
                            berdasarkan kriteria yang telah ditentukan secara ilmiah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Criteria Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Kriteria Penilaian</h2>
                <p class="lead mb-4">
                    Sistem menggunakan empat kriteria utama untuk menentukan prioritas penanganan kasus:
                </p>
                
                <div class="criteria-list">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x text-danger me-3"></i>
                        </div>
                        <div>
                            <h5>Tingkat Kerugian</h5>
                            <p class="text-muted">Besarnya kerugian materiil yang dialami korban akibat kejahatan online.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x text-warning me-3"></i>
                        </div>
                        <div>
                            <h5>Jumlah Korban</h5>
                            <p class="text-muted">Banyaknya korban yang terdampak dari kasus kejahatan online.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x text-info me-3"></i>
                        </div>
                        <div>
                            <h5>Urgensi</h5>
                            <p class="text-muted">Tingkat kepentingan atau seberapa cepat kasus harus segera ditangani.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-share-alt fa-2x text-success me-3"></i>
                        </div>
                        <div>
                            <h5>Potensi Penyebaran</h5>
                            <p class="text-muted">Kemungkinan kasus menyebar dan menimbulkan dampak yang lebih luas.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="bg-primary text-white p-5 rounded-3">
                    <h3 class="mb-4">Tentang Polsek Saribudolok</h3>
                    <p class="mb-4">
                        Polsek Saribudolok berkomitmen untuk memberikan pelayanan terbaik 
                        dalam penanganan kasus kejahatan online. Dengan menggunakan sistem 
                        pendukung keputusan berbasis AHP dan TOPSIS, kami dapat menentukan 
                        prioritas penanganan kasus secara lebih objektif dan efisien.
                    </p>
                    
                    <div class="stats-row">
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="fw-bold">24/7</h4>
                                <small>Layanan</small>
                            </div>
                            <div class="col-4">
                                <h4 class="fw-bold">100+</h4>
                                <small>Kasus Ditangani</small>
                            </div>
                            <div class="col-4">
                                <h4 class="fw-bold">95%</h4>
                                <small>Tingkat Keberhasilan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<?php if (!isset($_SESSION['user_id'])): ?>
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-6 fw-bold mb-4">Siap Memulai?</h2>
                <p class="lead mb-4">
                    Bergabunglah dengan sistem kami untuk mendapatkan akses ke fitur-fitur 
                    analisis prioritas penanganan kasus kejahatan online.
                </p>
                <button type="button" class="btn btn-primary btn-lg me-3" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
                <button type="button" class="btn btn-outline-light btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
