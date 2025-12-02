<x-guest-layout>
    {{-- THEME LOGIN SIPKAM (HIJAU TEAL) --}}
    <style>
        :root {
            --login-dark-1: #051F20;
            --login-dark-2: #0B2B26;
            --login-dark-3: #163832;
            --login-soft:   #8EB69B;
            --login-light:  #DAF1DE;
        }

        /* ===== BACKGROUND FULL PAGE ===== */
        body {
            background:
                radial-gradient(circle at 15% 25%, rgba(218, 241, 222, 0.55), transparent 55%),
                radial-gradient(circle at 85% 75%, rgba(142, 182, 155, 0.6), transparent 55%),
                linear-gradient(180deg, var(--login-light) 0%, var(--login-dark-3) 100%) !important;
            background-attachment: fixed;
        }

        /* ===== BRAND SIPKAM DI ATAS (LOGO + TITLE + SUBTITLE) ===== */
        /* Umumnya judul SIPKAM pakai .text-primary, subtitle pakai .text-muted */
        body .text-primary {
            /* judul SIPKAM => hijau gelap */
            color: #163832 !important;
        }

        body .text-muted {
            /* subtitle & teks kecil => hijau lebih soft */
            color: #235347 !important;
        }

        /* Wrapper putih default dari layout (bg-white) disamakan dengan background */
        body .bg-white {
            background-color: transparent !important;
            box-shadow: none !important;
            border: 0 !important;
        }

        /* ===== KARTU LOGIN UTAMA ===== */
        .sipkam-auth-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 32px 0 40px;
        }

        .sipkam-auth-card {
            position: relative;
            width: 100%;
            max-width: 420px;
            background: linear-gradient(145deg, rgba(5, 31, 32, 0.98), rgba(11, 43, 38, 0.98));
            border-radius: 22px;
            padding: 28px 26px 24px;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.6);
            color: #EAF7EF;
        }

        /* Garis gradasi tipis di atas kartu */
        .sipkam-auth-card::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 4px;
            border-radius: 22px 22px 0 0;
            background: linear-gradient(90deg, var(--login-soft), var(--login-light), #235347);
        }

        .sipkam-auth-card .text-primary {
            color: var(--login-light) !important;
        }

        .sipkam-auth-card .text-muted {
            color: rgba(218, 241, 222, 0.75) !important;
        }

        .sipkam-auth-card .form-label-modern {
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(218, 241, 222, 0.8);
        }

        .sipkam-auth-card .form-control-modern {
            border-radius: 14px;
            border: 1px solid rgba(142, 182, 155, 0.55);
            background: rgba(4, 29, 31, 0.92);
            color: #EAF7EF;
            font-size: 0.95rem;
        }

        .sipkam-auth-card .form-control-modern::placeholder {
            color: rgba(218, 241, 222, 0.55);
        }

        .sipkam-auth-card .form-control-modern:focus {
            outline: none;
            border-color: var(--login-soft);
            box-shadow: 0 0 0 1px rgba(142, 182, 155, 0.45);
        }

        .sipkam-auth-card .input-group .btn {
            border-radius: 14px;
            border-color: rgba(142, 182, 155, 0.7);
            background: rgba(4, 29, 31, 0.95);
            color: var(--login-light);
        }

        .sipkam-auth-card .input-group .btn:hover {
            background: rgba(8, 45, 45, 1);
        }

        .sipkam-auth-card .form-check-label {
            color: rgba(218, 241, 222, 0.8);
        }

        /* TOMBOL UTAMA */
        .sipkam-auth-card .btn-modern.btn-modern-primary {
            background: linear-gradient(135deg, #235347, var(--login-dark-1));
            border-radius: 999px;
            border: none;
            color: #EAF7EF;
            box-shadow: 0 10px 25px rgba(5, 31, 32, 0.55);
        }

        .sipkam-auth-card .btn-modern.btn-modern-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        /* QUICK ACTION BUTTONS */
        .sipkam-auth-card .btn-outline-info,
        .sipkam-auth-card .btn-outline-secondary {
            border-radius: 999px;
            border-color: rgba(142, 182, 155, 0.7);
            color: var(--login-light);
            background: transparent;
        }

        .sipkam-auth-card .btn-outline-info:hover,
        .sipkam-auth-card .btn-outline-secondary:hover {
            background: rgba(142, 182, 155, 0.2);
            color: #EAF7EF;
        }

        /* Spinner loading */
        .sipkam-auth-card .loading-spinner {
            width: 16px;
            height: 16px;
            border-radius: 999px;
            border: 2px solid rgba(218, 241, 222, 0.4);
            border-top-color: var(--login-light);
            animation: sipkam-spin 0.8s linear infinite;
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
        }

        @keyframes sipkam-spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 575.98px) {
            .sipkam-auth-wrapper {
                padding: 20px 0 28px;
            }
            .sipkam-auth-card {
                padding: 22px 18px 20px;
            }
        }
    </style>

    <div class="sipkam-auth-wrapper">
        <div class="sipkam-auth-card">
            <div class="text-center mb-4">
                <h2 class="h4 fw-bold text-primary mb-2">
                    <i class="fas fa-graduation-cap me-2"></i>Selamat Datang Kembali
                </h2>
                <p class="text-muted small">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email -->
               <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label-modern fw-semibold">
                <i class="fas fa-envelope me-2"></i>Email
            </label>
            <div class="input-group">
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control-modern @error('email') is-invalid @enderror"
                       placeholder="nama@domain.ac.id" required autofocus>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                Gunakan email resmi kampus
                </small>
            </div>
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


            </form>
        </div>
    </div>





    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const loginForm = document.getElementById('loginForm');

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
