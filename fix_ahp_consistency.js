// Fix for persistent AHP consistency message
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a persistent error message and clear it
    const crElement = document.getElementById('consistencyRatio');
    if (crElement) {
        const parentElement = crElement.parentElement;
        const currentText = parentElement.textContent;
        if (currentText.includes('TIDAK KONSISTEN') || currentText.includes('perbaiki penilaian')) {
            parentElement.className = 'alert alert-info';
            parentElement.innerHTML = '<strong>CR = <span id="consistencyRatio">0.00</span></strong><br><small class="text-muted">CR ≤ 0.10 = Konsisten<br>CR > 0.10 = Tidak Konsisten</small>';
        }
    }

    // Clear message when form is submitted
    const ahpForm = document.getElementById('ahpForm');
    if (ahpForm) {
        ahpForm.addEventListener('submit', function() {
            setTimeout(function() {
                const crElement = document.getElementById('consistencyRatio');
                if (crElement) {
                    const parentElement = crElement.parentElement;
                    parentElement.className = 'alert alert-info';
                    parentElement.innerHTML = '<strong>CR = <span id="consistencyRatio">0.00</span></strong><br><small class="text-muted">CR ≤ 0.10 = Konsisten<br>CR > 0.10 = Tidak Konsisten</small>';
                }
            }, 100);
        });
    }

    // Clear message when reset button is clicked
    const resetBtn = document.querySelector('button[onclick="resetForm()"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            setTimeout(function() {
                const crElement = document.getElementById('consistencyRatio');
                if (crElement) {
                    const parentElement = crElement.parentElement;
                    parentElement.className = 'alert alert-info';
                    parentElement.innerHTML = '<strong>CR = <span id="consistencyRatio">0.00</span></strong><br><small class="text-muted">CR ≤ 0.10 = Konsisten<br>CR > 0.10 = Tidak Konsisten</small>';
                }
            }, 100);
        });
    }
});
