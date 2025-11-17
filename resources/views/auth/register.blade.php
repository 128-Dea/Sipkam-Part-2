<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-primary mb-2">Buat Akun Baru</h2>
        <p class="text-muted small">Bergabunglah dengan sistem peminjaman barang kampus</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Role Selection -->
        <div class="mb-4">
            <label class="form-label-modern fw-semibold">Tipe Akun</label>
            <div class="row g-2">
                <div class="col-6">
                    <input
                        type="radio"
                        class="btn-check"
                        name="role"
                        id="role-mahasiswa"
                        value="mahasiswa"
                        autocomplete="off"
                        {{ old('role', 'mahasiswa') === 'mahasiswa' ? 'checked' : '' }}
                    >
                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="role-mahasiswa">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Mahasiswa
                    </label>
                </div>
                <div class="col-6">
                    <input
                        type="radio"
                        class="btn-check"
                        name="role"
                        id="role-petugas"
                        value="petugas"
                        autocomplete="off"
                        {{ old('role') === 'petugas' ? 'checked' : '' }}
                    >
                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="role-petugas">
                        <i class="fas fa-user-shield me-2"></i>
                        Petugas
                    </label>
                </div>
            </div>
            <small class="text-muted mt-1 d-block">
                <i class="fas fa-info-circle me-1"></i>
                Pilih tipe akun sesuai dengan peran Anda di kampus
            </small>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-3">
            <label class="form-label-modern fw-semibold" for="name">
                <i class="fas fa-user me-2"></i>Nama Lengkap
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control-modern @error('name') is-invalid @enderror"
                   placeholder="Masukkan nama lengkap Anda" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label-modern fw-semibold" for="email">
                <i class="fas fa-envelope me-2"></i>Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control-modern @error('email') is-invalid @enderror"
                   placeholder="contoh@domain.ac.id" required>
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Gunakan email resmi kampus (<span id="required-domain">{{ old('role', 'mahasiswa') === 'mahasiswa' ? '@mhs.unesa.ac.id' : '@admin.ac.id' }}</span>)
            </small>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Nomor Telepon -->
        <div class="mb-3">
            <label class="form-label-modern fw-semibold" for="phone">
                <i class="fas fa-phone me-2"></i>Nomor Telepon
            </label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                   class="form-control-modern @error('phone') is-invalid @enderror"
                   placeholder="081234567890" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label-modern fw-semibold" for="password">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control-modern @error('password') is-invalid @enderror"
                       placeholder="Minimal 8 karakter" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Password harus minimal 8 karakter dengan kombinasi huruf dan angka
            </small>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-4">
            <label class="form-label-modern fw-semibold" for="password_confirmation">
                <i class="fas fa-lock me-2"></i>Konfirmasi Password
            </label>
            <div class="input-group">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control-modern @error('password_confirmation') is-invalid @enderror"
                       placeholder="Ulangi password Anda" required>
                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Password Strength Indicator -->
        <div class="mb-3" id="password-strength" style="display: none;">
            <div class="progress" style="height: 6px;">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted" id="password-strength-text"></small>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label small" for="terms">
                    Saya menyetujui <a href="#" class="text-primary text-decoration-none">Syarat dan Ketentuan</a>
                    serta <a href="#" class="text-primary text-decoration-none">Kebijakan Privasi</a> yang berlaku
                </label>
            </div>
        </div>

        <!-- Tombol Register -->
        <div class="d-grid mb-3">
            <button class="btn-modern btn-modern-primary btn-lg fw-semibold" type="submit">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </button>
        </div>

        <!-- Link ke Login -->
        <div class="text-center">
            <p class="mb-0 text-muted">Sudah punya akun?
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">
                    <i class="fas fa-sign-in-alt me-1"></i>Masuk di sini
                </a>
            </p>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const emailInput = document.getElementById('email');
            const requiredDomain = document.getElementById('required-domain');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordStrength = document.getElementById('password-strength');
            const passwordStrengthBar = passwordStrength.querySelector('.progress-bar');
            const passwordStrengthText = document.getElementById('password-strength-text');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

            // Role-based email domain validation
            roleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const selectedRole = this.value;
                    if (selectedRole === 'mahasiswa') {
                        requiredDomain.textContent = '@mhs.unesa.ac.id';
                        emailInput.placeholder = 'nama@mhs.unesa.ac.id';
                    } else if (selectedRole === 'petugas') {
                        requiredDomain.textContent = '@admin.ac.id';
                        emailInput.placeholder = 'nama@admin.ac.id';
                    }
                    validateEmailDomain();
                });
            });

            // Real-time email domain validation
            emailInput.addEventListener('input', validateEmailDomain);

            function validateEmailDomain() {
                const email = emailInput.value;
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                const requiredDomain = selectedRole === 'mahasiswa' ? '@mhs.unesa.ac.id' : '@admin.ac.id';

                if (email && !email.endsWith(requiredDomain)) {
                    emailInput.setCustomValidity(`Email tidak valid untuk jenis akun ini. Gunakan domain ${requiredDomain}`);
                    emailInput.classList.add('is-invalid');
                    if (!emailInput.nextElementSibling || !emailInput.nextElementSibling.classList.contains('invalid-feedback')) {
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = `Email tidak valid untuk jenis akun ini. Gunakan domain ${requiredDomain}`;
                        emailInput.parentNode.insertBefore(feedback, emailInput.nextSibling);
                    }
                } else {
                    emailInput.setCustomValidity('');
                    emailInput.classList.remove('is-invalid');
                    const feedback = emailInput.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback') && feedback.textContent.includes('domain')) {
                        feedback.remove();
                    }
                }
            }

            // Password strength indicator
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let feedback = [];

                if (password.length >= 8) strength += 25;
                else feedback.push('Minimal 8 karakter');

                if (/[a-z]/.test(password)) strength += 25;
                else feedback.push('Huruf kecil');

                if (/[A-Z]/.test(password)) strength += 25;
                else feedback.push('Huruf besar');

                if (/[0-9]/.test(password)) strength += 25;
                else feedback.push('Angka');

                passwordStrength.style.display = password ? 'block' : 'none';
                passwordStrengthBar.style.width = strength + '%';

                if (strength < 50) {
                    passwordStrengthBar.className = 'progress-bar bg-danger';
                    passwordStrengthText.textContent = 'Kekuatan password: Lemah - ' + feedback.join(', ');
                } else if (strength < 75) {
                    passwordStrengthBar.className = 'progress-bar bg-warning';
                    passwordStrengthText.textContent = 'Kekuatan password: Sedang';
                } else {
                    passwordStrengthBar.className = 'progress-bar bg-success';
                    passwordStrengthText.textContent = 'Kekuatan password: Kuat';
                }
            });

            // Password confirmation validation
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Password tidak cocok');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Form submission validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const selectedRole = document.querySelector('input[name="role"]:checked');
                if (!selectedRole) {
                    e.preventDefault();
                    showAlert('Silakan pilih tipe akun terlebih dahulu', 'warning');
                    return;
                }

                const terms = document.getElementById('terms');
                if (!terms.checked) {
                    e.preventDefault();
                    showAlert('Silakan setujui syarat dan ketentuan terlebih dahulu', 'warning');
                    return;
                }

                validateEmailDomain();
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });

            // Initialize validation
            validateEmailDomain();
        });
    </script>
</x-guest-layout>
