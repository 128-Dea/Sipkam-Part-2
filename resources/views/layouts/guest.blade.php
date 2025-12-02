<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Peminjaman Kampus') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            /* === PALET HIJAU TEAL SIPKAM === */
            --teal-900: #051F20;
            --teal-800: #0B2B26;
            --teal-700: #163832;
            --teal-600: #235347;
            --teal-300: #8EB69B;
            --teal-100: #DAF1DE;

            --primary-color: var(--teal-600);
            --primary-light: var(--teal-300);
            --primary-dark: var(--teal-900);
            --secondary-color: var(--teal-100);
            --accent-color: var(--teal-300);
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-primary: var(--teal-900);
            --text-secondary: #355b4f;
            --bg-light: var(--teal-100);
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 35px rgba(0, 0, 0, 0.35);
            --border-radius: 12px;
            --border-radius-lg: 24px;
        }

        body {
            font-family: 'Inter', sans-serif;
            /* Gradasi hijau terang → gelap */
            background:
                radial-gradient(circle at 10% 0%, rgba(218, 241, 222, 0.9) 0%, transparent 45%),
                radial-gradient(circle at 90% 100%, rgba(11, 43, 38, 0.85) 0%, transparent 45%),
                linear-gradient(180deg, #DAF1DE 0%, #8EB69B 45%, #163832 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(142, 182, 155, 0.28) 0%, transparent 55%),
                radial-gradient(circle at 80% 20%, rgba(5, 31, 32, 0.3) 0%, transparent 45%),
                radial-gradient(circle at 45% 35%, rgba(35, 83, 71, 0.25) 0%, transparent 55%);
            pointer-events: none;
        }

        .guest-container {
            position: relative;
            z-index: 1;
            padding: 2rem 1rem;
        }

        /* ====== BRAND (LOGO SIPKAM) ====== */
        .brand-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: radial-gradient(circle at 30% 0%, var(--teal-300) 0%, var(--teal-600) 40%, var(--teal-900) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 18px 40px rgba(5, 31, 32, 0.55);
            transition: all 0.3s ease;
        }

        .brand-logo:hover {
            transform: translateY(-3px) scale(1.03);
        }

        .brand-logo i {
            font-size: 2.5rem;
            color: #fdfefc;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
            background: linear-gradient(135deg, var(--teal-300), var(--teal-900));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            color: rgba(5, 31, 32, 0.72);
            font-size: 0.98rem;
            font-weight: 500;
        }

        /* ====== SPLASH SCREEN (LOADING) ====== */
        .splash-screen {
            position: fixed;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background:
                radial-gradient(circle at 20% 20%, rgba(142, 182, 155, 0.25), transparent 40%),
                radial-gradient(circle at 80% 30%, rgba(5, 31, 32, 0.28), transparent 40%),
                linear-gradient(135deg, #DAF1DE 0%, #8EB69B 45%, #163832 100%);
            z-index: 2000;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        .splash-screen.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .splash-logo {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 0%, var(--teal-300) 0%, var(--teal-600) 40%, var(--teal-900) 100%);
            display: grid;
            place-items: center;
            box-shadow: 0 22px 45px rgba(5, 31, 32, 0.6);
            animation: pulse 1.8s ease-in-out infinite;
        }

        .splash-logo i {
            font-size: 2.75rem;
            color: #fdfefc;
        }

        .splash-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: var(--teal-900);
        }

        .splash-subtitle {
            font-size: 0.95rem;
            color: rgba(5, 31, 32, 0.75);
            font-weight: 600;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(142, 182, 155, 0.35); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 16px rgba(142, 182, 155, 0.10); }
        }

        /* ====== WRAPPER + FORM CARD ====== */

        /* frame besar (yang tadinya putih tinggi) */
        .auth-card {
            background: linear-gradient(180deg, rgba(218, 241, 222, 0.98) 0%, rgba(142, 182, 155, 0.92) 45%, rgba(22, 56, 50, 0.98) 100%);
            border-radius: 32px;
            padding: 3px; /* buat efek border gradasi tipis */
            box-shadow:
                0 28px 45px rgba(5, 31, 32, 0.65),
                0 0 0 1px rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: visible;
        }

        /* hilangkan bar warna di atas card yang lama */
        .auth-card::before {
            display: none;
        }

        /* inner card gelap, tempat form login */
        .auth-card-body {
            background: radial-gradient(circle at 0% 0%, #163832 0%, #051F20 55%, #051F20 100%);
            border-radius: 28px;
            padding: 2.25rem 2.5rem;
            color: #EAF7EF;
            box-shadow:
                0 20px 35px rgba(0, 0, 0, 0.45),
                0 0 0 1px rgba(8, 43, 38, 0.6);
        }

        /* ====== FORM DI DALAM CARD GELAP ====== */

        .auth-card-body h1,
        .auth-card-body h2,
        .auth-card-body h3,
        .auth-card-body h4,
        .auth-card-body h5 {
            color: #EAF7EF;
        }

        .auth-card-body .text-muted {
            color: rgba(218, 241, 222, 0.78) !important;
        }

        .form-label-modern {
            font-weight: 600;
            color: #EAF7EF;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .form-control-modern {
            border: 1px solid #163832;
            border-radius: 999px;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            background: #0B2B26;
            color: #EAF7EF;
        }

        .form-control-modern::placeholder {
            color: rgba(234, 247, 239, 0.6);
        }

        .form-control-modern:focus {
            border-color: var(--teal-300);
            box-shadow: 0 0 0 2px rgba(142, 182, 155, 0.3);
            outline: none;
            background: #0B2B26;
            color: #EAF7EF;
        }

        .input-group .btn-outline-secondary {
            border-radius: 999px;
            border-color: #163832;
            background-color: #0B2B26;
            color: #EAF7EF;
        }

        .input-group .btn-outline-secondary:hover {
            background-color: #163832;
            border-color: #163832;
            color: #EAF7EF;
        }

        .form-check-input {
            border-radius: 6px;
            border-color: #163832;
            background-color: transparent;
        }

        .form-check-input:checked {
            background-color: var(--teal-300);
            border-color: var(--teal-300);
        }

        .form-check-label {
            color: rgba(218, 241, 222, 0.85);
        }

        .auth-card-body a.text-primary,
        .auth-card-body a.text-primary:visited {
            color: var(--teal-300) !important;
        }

        .auth-card-body a.text-primary:hover {
            color: #DAF1DE !important;
        }

        .btn-modern {
            border-radius: 999px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--teal-300), var(--teal-900));
            color: #fdfefc;
            box-shadow: 0 10px 25px rgba(5, 31, 32, 0.55);
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 35px rgba(5, 31, 32, 0.7);
            filter: brightness(1.03);
        }

        .btn-outline-info,
        .btn-outline-secondary {
            border-radius: 999px;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ====== FLOATING SHAPES ====== */
        .floating-shapes {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.12;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 12%;
            left: 10%;
            width: 60px;
            height: 60px;
            background: var(--teal-300);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 25%;
            right: 12%;
            width: 44px;
            height: 44px;
            background: var(--teal-600);
            border-radius: 22px;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 18%;
            left: 22%;
            width: 84px;
            height: 84px;
            background: var(--teal-900);
            border-radius: 42px;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-18px) rotate(180deg); }
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            .guest-container {
                padding: 1.5rem 1rem;
            }

            .auth-card-body {
                padding: 2rem 1.5rem;
            }

            .brand-title {
                font-size: 1.6rem;
            }

            .brand-logo {
                width: 64px;
                height: 64px;
            }

            .brand-logo i {
                font-size: 2.1rem;
            }
        }

        @media (max-width: 576px) {
            .auth-card {
                border-radius: 24px;
            }

            .auth-card-body {
                padding: 1.75rem 1.25rem;
            }

            .brand-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div id="splash-screen" class="splash-screen">
        <div class="splash-logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="text-center">
            <div class="splash-title">SIPKAM</div>
            <div class="splash-subtitle">Sistem Peminjaman Kampus</div>
        </div>
    </div>

    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container guest-container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="brand-section">
                    <div class="brand-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1 class="brand-title">SIPKAM</h1>
                    <p class="brand-subtitle">Sistem Informasi Peminjaman Kampus</p>
                </div>

                <div class="auth-card">
                    <div class="auth-card-body">
                        {{ $slot }}
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Sistem aman & terenkripsi • Didukung Universitas
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Utility functions for auth pages
        function showAlert(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' :
                              type === 'error' ? 'alert-danger' :
                              type === 'warning' ? 'alert-warning' : 'alert-info';

            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show mt-3`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            const cardBody = document.querySelector('.auth-card-body');
            if (cardBody) {
                cardBody.insertBefore(alert, cardBody.firstChild);
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            }
        }

        // Add loading state to buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.innerHTML = '<span class="loading-spinner"></span>Memproses...';
                        submitBtn.disabled = true;
                    }
                });
            });
        });

        // Splash screen handling
        window.addEventListener('load', function() {
            const splash = document.getElementById('splash-screen');
            if (!splash) return;

            setTimeout(() => {
                splash.classList.add('fade-out');
            }, 650);

            splash.addEventListener('transitionend', () => {
                splash.style.display = 'none';
            }, { once: true });
        });
    </script>
</body>
</html>
