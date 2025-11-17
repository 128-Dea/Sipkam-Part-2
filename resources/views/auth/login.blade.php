<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-primary mb-2">
            <i class="fas fa-graduation-cap me-2"></i>Selamat Datang Kembali
        </h2>
        <p class="text-muted small">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label-modern fw-semibold">
                <i class="fas fa-envelope me-2"></i>Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control-modern @error('email') is-invalid @enderror"
                   placeholder="nama@domain.ac.id" required autofocus>
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Gunakan email resmi kampus
            </small>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label-modern fw-semibold">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control-modern @error('password') is-invalid @enderror"
                       placeholder="Masukkan password Anda" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember">
                <label class="form-check-label text-muted small" for="remember_me">
                    <i class="fas fa-clock me-1"></i>Ingat saya
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary fw-semibold">
                    <i class="fas fa-key me-1"></i>Lupa password?
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn-modern btn-modern-primary btn-lg fw-semibold">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </div>

        <!-- Link ke Register -->
        <div class="text-center mb-3">
            <p class="mb-0 text-muted">Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">
                    <i class="fas fa-user-plus me-1"></i>Daftar sekarang
                </a>
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="text-center">
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-info btn-sm w-100" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="fas fa-question-circle me-1"></i>Bantuan
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-search me-1"></i>Cek Status
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-question-circle text-info me-2"></i>Bantuan Login
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-graduation-cap me-2"></i>Mahasiswa
                            </h6>
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    Email: nama@mhs.unesa.ac.id
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-key text-muted me-2"></i>
                                    Password: Sesuai yang didaftarkan
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info-circle text-muted me-2"></i>
                                    Status: Aktif setelah verifikasi
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user-shield me-2"></i>Petugas
                            </h6>
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    Email: nama@admin.ac.id
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-key text-muted me-2"></i>
                                    Password: Diberikan admin
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info-circle text-muted me-2"></i>
                                    Status: Aktif setelah aktivasi
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tips:</strong> Jika lupa password, klik "Lupa password?" untuk reset.
                        Untuk bantuan lebih lanjut, hubungi admin sistem.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Check Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-search text-secondary me-2"></i>Cek Status Akun
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <div class="mb-3">
                            <label class="form-label-modern">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" class="form-control-modern" id="statusEmail"
                                   placeholder="Masukkan email yang digunakan mendaftar" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn-modern btn-modern-primary">
                                <i class="fas fa-search me-2"></i>Cek Status
                            </button>
                        </div>
                    </form>
                    <div id="statusResult" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const loginForm = document.getElementById('loginForm');
            const statusForm = document.getElementById('statusForm');
            const statusResult = document.getElementById('statusResult');

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Login form submission
            loginForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Sedang masuk...';
                submitBtn.disabled = true;

                // Re-enable after 3 seconds if no response (fallback)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Masuk';
                        submitBtn.disabled = false;
                    }
                }, 3000);
            });

            // Status check form
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('statusEmail').value;
                const submitBtn = this.querySelector('button[type="submit"]');

                submitBtn.innerHTML = '<span class="loading-spinner"></span> Mencari...';
                submitBtn.disabled = true;

                // Simulate API call (replace with actual endpoint)
                setTimeout(() => {
                    statusResult.style.display = 'block';
                    statusResult.innerHTML = `
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Status:</strong><br>
                            Email: ${email}<br>
                            Status: <span class="badge bg-warning">Menunggu Verifikasi</span><br>
                            <small class="text-muted">Akun Anda sedang dalam proses verifikasi oleh admin. Silakan tunggu 1-2 hari kerja.</small>
                        </div>
                    `;

                    submitBtn.innerHTML = '<i class="fas fa-search me-2"></i>Cek Status';
                    submitBtn.disabled = false;
                }, 1500);
            });

            // Auto-focus email field
            document.getElementById('email').focus();

            // Enter key navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    const activeElement = document.activeElement;
                    if (activeElement.id === 'email') {
                        document.getElementById('password').focus();
                    }
                }
            });
        });
    </script>
</x-guest-layout>
