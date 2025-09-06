<?php
// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    return;
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));

// Function to check if menu item is active
function isActive($page, $current_page, $current_dir = '') {
    if ($current_dir === 'admin' && strpos($page, 'admin/') !== false) {
        return basename($page) === $current_page ? 'active bg-primary' : '';
    } elseif ($current_dir !== 'admin' && strpos($page, 'admin/') === false) {
        return basename($page) === $current_page ? 'active bg-primary' : '';
    }
    return '';
}

// Determine base path based on current directory
$base_path = '';
if ($current_dir === 'pages') {
    $base_path = '../';
} elseif ($current_dir === 'admin') {
    $base_path = '../';
} elseif ($current_dir === 'auth') {
    $base_path = '../';
}
?>

<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="sidebar">
    <div class="position-sticky pt-3">
        <!-- Sidebar Header -->
        <div class="sidebar-header px-3 mb-3">
            <h6 class="text-white-50 text-uppercase fw-bold">
                <i class="fas fa-user-circle me-2"></i>
                <?php echo ucfirst($_SESSION['role']); ?> Menu
            </h6>
            <hr class="text-white-50">
        </div>

        <!-- Navigation Menu -->
        <ul class="nav flex-column px-2">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Admin Menu Items -->
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('dashboard.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/dashboard.php">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('manage_criteria.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/manage_criteria.php">
                        <i class="fas fa-list-alt me-3"></i>
                        <span>Kelola Data dan Kriteria</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('manage_sub_criteria.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/manage_sub_criteria.php">
                        <i class="fas fa-list me-3"></i>
                        <span>Kelola Data dan Sub Kriteria</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('manage_alternatives.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/manage_alternatives.php">
                        <i class="fas fa-database me-3"></i>
                        <span>Kelola Data dan Alternatif</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('input_case.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/input_case.php">
                        <i class="fas fa-plus-circle me-3"></i>
                        <span>Input Kasus</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('ahp.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/ahp.php">
                        <i class="fas fa-balance-scale me-3"></i>
                        <span>Perhitungan AHP</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('topsis.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/topsis.php">
                        <i class="fas fa-chart-line me-3"></i>
                        <span>Perhitungan TOPSIS</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('results.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/results.php">
                        <i class="fas fa-chart-bar me-3"></i>
                        <span>HASIL</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('manage_users.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>admin/manage_users.php">
                        <i class="fas fa-users me-3"></i>
                        <span>Kelola User</span>
                    </a>
                </li>
                
            <?php else: ?>
                <!-- Client Menu Items -->
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('dashboard.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/dashboard.php">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('input_case.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/input_case.php">
                        <i class="fas fa-plus-circle me-3"></i>
                        <span>Input Kasus</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('ahp.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/ahp.php">
                        <i class="fas fa-balance-scale me-3"></i>
                        <span>Perhitungan AHP</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('topsis.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/topsis.php">
                        <i class="fas fa-chart-line me-3"></i>
                        <span>Perhitungan TOPSIS</span>
                    </a>
                </li>
                
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded <?php echo isActive('results.php', $current_page, $current_dir); ?>" 
                       href="<?php echo $base_path; ?>pages/results.php">
                        <i class="fas fa-chart-bar me-3"></i>
                        <span>HASIL</span>
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Logout Menu (Common for both roles) -->
            <li class="nav-item mt-3 pt-3 border-top border-secondary">
                <a class="nav-link text-white d-flex align-items-center py-2 px-3 rounded" 
                   href="<?php echo $base_path; ?>auth/logout.php">
                    <i class="fas fa-sign-out-alt me-3"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
        
        <!-- User Info Footer -->
        <div class="sidebar-footer mt-auto p-3 border-top border-secondary">
            <div class="text-white-50 small">
                <i class="fas fa-user me-2"></i>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </div>
            <div class="text-white-50 small">
                <i class="fas fa-shield-alt me-2"></i>
                <?php echo ucfirst($_SESSION['role']); ?>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar Toggle Button for Mobile -->
<button class="btn btn-dark d-md-none position-fixed" 
        style="top: 10px; left: 10px; z-index: 1050;" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#sidebar" 
        aria-controls="sidebar" 
        aria-expanded="false" 
        aria-label="Toggle sidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay d-md-none" id="sidebarOverlay"></div>

<style>
/* Sidebar Specific Styles */
.sidebar {
    min-height: 100vh;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

.sidebar .nav-link {
    transition: all 0.3s ease;
    border-radius: 8px;
    margin-bottom: 2px;
}

.sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    background-color: #0d6efd !important;
    box-shadow: 0 2px 4px rgba(13,110,253,0.3);
}

.sidebar-header h6 {
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.sidebar-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0,0,0,0.2);
}

/* Mobile Responsive */
@media (max-width: 767.98px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 280px;
        z-index: 1040;
        transition: left 0.3s ease;
    }
    
    .sidebar.show {
        left: 0;
    }
    
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1030;
        display: none;
    }
    
    .sidebar-overlay.show {
        display: block;
    }
}

/* Smooth transitions */
.sidebar .nav-link i {
    width: 20px;
    text-align: center;
}

.sidebar .nav-link span {
    font-weight: 500;
}
</style>

<script>
// Mobile sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('[data-bs-toggle="collapse"]');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
});
</script>
