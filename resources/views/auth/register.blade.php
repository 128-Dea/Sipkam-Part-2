<x-guest-layout>

    {{-- STYLING KHUSUS HALAMAN REGISTER (PALET HIJAU SAMA DENGAN LOGIN) --}}
    <style>
        /* pakai variabel dari guest-layout: --teal-300, --teal-600, dll */

        /* Perbesar input field & tetap pakai styling hijau gelap dari layout */
        .auth-card-body .form-control-modern {
            min-height: 54px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        /* Judul & teks yang pakai class .text-primary -> jadi teal lembut */
        .auth-card-body .text-primary {
            color: var(--teal-300) !important;
        }

        /* Tombol pilihan Mahasiswa / Petugas (btn-outline-primary) pakai warna hijau */
        .auth-card-body .btn-outline-primary {
            border-radius: 999px;
            border-color: var(--teal-300);
            color: var(--teal-300);
            background-color: transparent;
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            font-weight: 600;
        }

        .auth-card-body .btn-outline-primary:hover,
        .auth-card-body .btn-outline-primary:focus {
            background-color: rgba(142, 182, 155, 0.12);
            border-color: var(--teal-300);
            color: #DAF1DE;
            box-shadow: 0 0 0 2px rgba(142, 182, 155, 0.35);
        }

        /* Saat radio checked => tombol jadi blok teal solid seperti di login */
        .auth-card-body .btn-check:checked + .btn-outline-primary,
        .auth-card-body .btn-check:active + .btn-outline-primary,
        .auth-card-body .btn-outline-primary.active {
            background: linear-gradient(135deg, var(--teal-300), var(--teal-600));
            border-color: var(--teal-300);
            color: #051F20;
            box-shadow: 0 10px 20px rgba(5, 31, 32, 0.55);
        }

        /* Caption kecil tetap mudah dibaca di background gelap */
        .auth-card-body small.text-muted {
            color: rgba(218, 241, 222, 0.78) !important;
        }

        /* Icon tombol show password tetap menyatu dengan card gelap */
        .auth-card-body .btn-outline-secondary {
            border-radius: 999px;
        }
    </style>

    {{-- Judul --}}
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-primary mb-2">Buat Akun Baru</h2>
        <p class="text-muted small mb-0">
            Bergabunglah dengan sistem peminjaman barang kampus
        </p>
    </div>

    {{-- Batasi lebar form & letakkan di tengah --}}
    <div class="mx-auto" style="max-width: 480px;">

        {{-- FORM: logika tetap sama --}}
        <form method="POST" action="{{ route('register') }}" id="registerForm" class="p-4 p-md-5">
            @csrf

            <!-- Role Selection -->
            <div class="mb-4">
                <label class="form-label-modern fw-semibold d-flex align-items-center gap-2 mb-2">
                    <span>Tipe Akun</span>
                </label>
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
                        <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-2" for="role-mahasiswa">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <span class="small fw-semibold">Mahasiswa</span>
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
                        <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-2" for="role-petugas">
                            <i class="fas fa-user-shield me-2"></i>
                            <span class="small fw-semibold">Petugas</span>
                        </label>
                    </div>
                </div>
                <small class="text-muted mt-1 d-block ms-1">
                    <i class="fas fa-info-circle me-1"></i>
                    Pilih tipe akun sesuai dengan peran Anda di kampus
                </small>
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label class="form-label-modern fw-semibold d-flex align-items-center gap-2" for="name">
                    <i class="fas fa-user"></i>
                    <span>Nama Lengkap</span>
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="form-control-modern @error('name') is-invalid @enderror"
                       placeholder="Masukkan nama lengkap Anda" required autofocus>
                @error('name')<div class="invalid-feedback ms-1">{{ $message }}</div>@enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label-modern fw-semibold d-flex align-items-center gap-2" for="email">
                    <i class="fas fa-envelope"></i>
                    <span>Email</span>
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control-modern @error('email') is-invalid @enderror"
                       placeholder="contoh@domain.ac.id" required>
                <small class="text-muted d-block mt-1 ms-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Gunakan email resmi kampus
                    (<span id="required-domain">
                        {{ old('role', 'mahasiswa') === 'mahasiswa' ? '@mhs.unesa.ac.id' : '@admin.ac.id' }}
                    </span>)
                </small>
                @error('email')<div class="invalid-feedback ms-1">{{ $message }}</div>@enderror
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-3">
                <label class="form-label-modern fw-semibold d-flex align-items-center gap-2" for="phone">
                    <i class="fas fa-phone"></i>
                    <span>Nomor Telepon</span>
                </label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                       class="form-control-modern @error('phone') is-invalid @enderror"
                       placeholder="081234567890" required>
                @error('phone')<div class="invalid-feedback ms-1">{{ $message }}</div>@enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label-modern fw-semibold d-flex align-items-center gap-2" for="password">
                    <i class="fas fa-lock"></i>
                    <span>Password</span>
                </label>
                <div class="input-group">
                    <input id="password" type="password" name="password"
                           class="form-control-modern @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small class="text-muted d-block mt-1 ms-4">
                    <i class="fas fa-shield-alt me-1"></i>
                    Password harus minimal 8 karakter dengan kombinasi huruf dan angka
                </small>
                @error('password')<div class="invalid-feedback ms-1">{{ $message }}</div>@enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label class="form-label-modern fw-semibold d-flex align-items-center gap-2" for="password_confirmation">
                    <i class="fas fa-lock"></i>
                    <span>Konfirmasi Password</span>
                </label>
                <div class="input-group">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-control-modern @error('password_confirmation') is-invalid @enderror"
                           placeholder="Ulangi password Anda" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')<div class="invalid-feedback ms-1">{{ $message }}</div>@enderror
            </div>

            <!-- Password Strength Indicator -->
            <div class="mb-3" id="password-strength" style="display: none;">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted" id="password-strength-text"></small>
            </div>

            <!-- Tombol Register -->
            <div class="d-grid mb-3">
                <button class="btn-modern btn-modern-primary btn-lg fw-semibold" type="submit">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
            </div>

            <!-- Link ke Login -->
            <div class="text-center">
                <p class="mb-0 text-muted">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">
                        <i class="fas fa-sign-in-alt me-1"></i>Masuk di sini
                    </a>
                </p>
            </div>

            {{-- Footer kecil di bawah form --}}
            <div class="text-center mt-3">
                <small class="text-muted">
                    Sistem aman &amp; terenkripsi · Didukung Universitas
                </small>
            </div>
        </form>
    </div>

    {{-- SCRIPT: PERSIS SAMA, TIDAK DIUBAH --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const emailInput = document.getElementById('email');
            const requiredDomainSpan = document.getElementById('required-domain');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordStrength = document.getElementById('password-strength');
            const passwordStrengthBar = passwordStrength.querySelector('.progress-bar');
            const passwordStrengthText = document.getElementById('password-strength-text');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const form = document.getElementById('registerForm');

            function getSelectedRole() {
                const checked = document.querySelector('input[name="role"]:checked');
                return checked ? checked.value : 'mahasiswa';
            }

            function getRequiredDomain() {
                return getSelectedRole() === 'mahasiswa'
                    ? '@mhs.unesa.ac.id'
                    : '@admin.ac.id';
            }

            function updateEmailHint() {
                const domain = getRequiredDomain();
                requiredDomainSpan.textContent = domain;
                emailInput.placeholder = getSelectedRole() === 'mahasiswa'
                    ? 'nama@mhs.unesa.ac.id'
                    : 'nama@admin.ac.id';
            }

            function validateEmailDomain() {
                const email = emailInput.value.trim();
                const domain = getRequiredDomain();

                let domainFeedback = emailInput.parentNode.querySelector('.domain-error');
                if (!domainFeedback) {
                    domainFeedback = document.createElement('div');
                    domainFeedback.className = 'invalid-feedback domain-error';
                    emailInput.parentNode.insertBefore(domainFeedback, emailInput.nextSibling);
                }

                if (email && !email.endsWith(domain)) {
                    emailInput.setCustomValidity('Email tidak valid untuk jenis akun ini.');
                    emailInput.classList.add('is-invalid');
                    domainFeedback.textContent =
                        `Email tidak valid untuk jenis akun ini. Gunakan domain ${domain}`;
                } else {
                    emailInput.setCustomValidity('');
                    emailInput.classList.remove('is-invalid');
                    domainFeedback.textContent = '';
                }
            }

            roleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateEmailHint();
                    validateEmailDomain();
                });
            });

            emailInput.addEventListener('input', validateEmailDomain);
            updateEmailHint();

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength += 25;
                if (/[a-z]/.test(password)) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;

                passwordStrength.style.display = password ? 'block' : 'none';
                passwordStrengthBar.style.width = strength + '%';

                if (strength < 50) {
                    passwordStrengthBar.className = 'progress-bar bg-danger';
                    passwordStrengthText.textContent = 'Kekuatan password: Lemah';
                } else if (strength < 75) {
                    passwordStrengthBar.className = 'progress-bar bg-warning';
                    passwordStrengthText.textContent = 'Kekuatan password: Sedang';
                } else {
                    passwordStrengthBar.className = 'progress-bar bg-success';
                    passwordStrengthText.textContent = 'Kekuatan password: Kuat';
                }
            });

            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Password tidak cocok');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
                confirmPasswordInput.type = type;
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            form.addEventListener('submit', function(e) {
                validateEmailDomain();

                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                form.classList.add('was-validated');
            });
        });
    </script>
</x-guest-layout>
