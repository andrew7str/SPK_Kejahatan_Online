// Main JavaScript for DSS Online Crime System

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Loading spinner for forms
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            if (form && form.checkValidity()) {
                // Don't prevent default - let form submit normally
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...';
                // Don't disable the button immediately, let form submit first
                setTimeout(() => {
                    this.disabled = true;
                }, 100);
            }
        });
    });

    // Sidebar functionality is handled in sidebar.js

    // Dynamic table search
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.table-container').querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });

    // Confirmation dialogs
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
            
            if (confirm(message)) {
                window.location.href = this.href;
            }
        });
    });

    // Number formatting for currency
    const currencyInputs = document.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                this.value = 'Rp ' + value;
            }
        });
    });

    // AHP Matrix validation
    const ahpInputs = document.querySelectorAll('.ahp-input');
    ahpInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateAHPMatrix();
        });
    });

    // TOPSIS calculation helpers
    window.calculateTOPSIS = function() {
        const alternatives = document.querySelectorAll('.alternative-row');
        const criteria = document.querySelectorAll('.criteria-weight');
        
        if (alternatives.length === 0 || criteria.length === 0) {
            alert('Data tidak lengkap untuk perhitungan TOPSIS');
            return;
        }

        // Show loading
        const calculateBtn = document.querySelector('#calculateTOPSIS');
        if (calculateBtn) {
            calculateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung...';
            calculateBtn.disabled = true;
        }

        // Simulate calculation delay
        setTimeout(() => {
            // Reset button
            if (calculateBtn) {
                calculateBtn.innerHTML = '<i class="fas fa-calculator me-2"></i>Hitung TOPSIS';
                calculateBtn.disabled = false;
            }
            
            // Show results (this would be replaced with actual calculation)
            showTOPSISResults();
        }, 2000);
    };

    // Show TOPSIS results
    function showTOPSISResults() {
        const resultsContainer = document.querySelector('#topsisResults');
        if (resultsContainer) {
            resultsContainer.style.display = 'block';
            resultsContainer.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Validate AHP Matrix consistency
    function validateAHPMatrix() {
        const inputs = document.querySelectorAll('.ahp-input');
        let isValid = true;
        
        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (isNaN(value) || value <= 0) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        const submitBtn = document.querySelector('#submitAHP');
        if (submitBtn) {
            submitBtn.disabled = !isValid;
        }

        return isValid;
    }

    // Chart initialization (if Chart.js is loaded)
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }

    // Initialize charts
    function initializeCharts() {
        // Priority Chart
        const priorityChart = document.getElementById('priorityChart');
        if (priorityChart) {
            new Chart(priorityChart, {
                type: 'bar',
                data: {
                    labels: ['Kasus A', 'Kasus B', 'Kasus C'],
                    datasets: [{
                        label: 'Skor Prioritas',
                        data: [0.65, 0.26, 0.10],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 205, 86, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 205, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1
                        }
                    }
                }
            });
        }

        // Criteria Weight Chart
        const criteriaChart = document.getElementById('criteriaChart');
        if (criteriaChart) {
            new Chart(criteriaChart, {
                type: 'doughnut',
                data: {
                    labels: ['Tingkat Kerugian', 'Jumlah Korban', 'Urgensi', 'Potensi Penyebaran'],
                    datasets: [{
                        data: [0.57, 0.24, 0.13, 0.06],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    // Export functionality
    window.exportToExcel = function() {
        const table = document.querySelector('.results-table');
        if (!table) {
            alert('Tidak ada data untuk diekspor');
            return;
        }

        // Simple CSV export
        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = [];
            cols.forEach(col => {
                rowData.push(col.textContent.trim());
            });
            csv.push(rowData.join(','));
        });

        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = 'hasil_prioritas_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    };

    // Print functionality
    window.printResults = function() {
        const printContent = document.querySelector('.results-container');
        if (!printContent) {
            alert('Tidak ada data untuk dicetak');
            return;
        }

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Hasil Prioritas Penanganan Kasus</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .footer { margin-top: 30px; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Sistem Pendukung Keputusan</h2>
                        <h3>Prioritas Penanganan Kasus Kejahatan Online</h3>
                        <p>Polsek Saribudolok - ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                    ${printContent.innerHTML}
                    <div class="footer">
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    };

    // Real-time clock
    function updateClock() {
        const clockElement = document.querySelector('.current-time');
        if (clockElement) {
            const now = new Date();
            clockElement.textContent = now.toLocaleString('id-ID');
        }
    }

    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Initial call

    // Notification system
    window.showNotification = function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    };

    // Initialize page-specific functionality
    const currentPage = window.location.pathname.split('/').pop();
    
    switch(currentPage) {
        case 'ahp.php':
            initializeAHPPage();
            break;
        case 'topsis.php':
            initializeTOPSISPage();
            break;
        case 'results.php':
            initializeResultsPage();
            break;
        case 'dashboard.php':
            initializeDashboard();
            break;
    }

    function initializeAHPPage() {
        console.log('AHP page initialized');
        // AHP-specific initialization
    }

    function initializeTOPSISPage() {
        console.log('TOPSIS page initialized');
        // TOPSIS-specific initialization
    }

    function initializeResultsPage() {
        console.log('Results page initialized');
        // Results-specific initialization
    }

    function initializeDashboard() {
        console.log('Dashboard initialized');
        // Dashboard-specific initialization
    }
});

// Global utility functions
window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

window.formatNumber = function(number, decimals = 2) {
    return parseFloat(number).toFixed(decimals);
};

window.validateEmail = function(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

window.validatePhone = function(phone) {
    const re = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
    return re.test(phone);
};

// Error handling
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    // You could send this to a logging service
});

// Service Worker registration (if available)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('SW registered: ', registration);
            })
            .catch(function(registrationError) {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
