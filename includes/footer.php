<!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-shield-alt me-2"></i>SPK Kejahatan Online</h5>
                    <p class="mb-0">Sistem Pendukung Keputusan untuk Prioritas Penanganan Kasus Kejahatan Online menggunakan metode AHP dan TOPSIS.</p>
                </div>
                <div class="col-md-6">
                    <h6>Kontak</h6>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Polsek Saribudolok, Kabupaten Simalungun</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>+62 xxx-xxxx-xxxx</p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i>info@polseksaribudolok.go.id</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Create By : Mr.exe</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <script>
        // Form validation for login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('loginUsername').value;
            const password = document.getElementById('loginPassword').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Mohon isi semua field!');
            }
        });

        // Form validation for register
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const username = document.getElementById('registerUsername').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (!username || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Mohon isi semua field!');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
        });
    </script>
</body>
</html>
