@extends('layouts.app')

@php($statistik = $statistik ?? [])
@php($aktivitas = $aktivitas ?? [])
@php($barangDipinjam = $barangDipinjam ?? collect())
@php($riwayatSelesai = $statistik['selesai'] ?? 0)
@php($aktif = $statistik['aktif'] ?? 0)
@php($menunggu = $statistik['menunggu'] ?? 0)
@php($keluhanDiajukan = $statistik['keluhan'] ?? ($statistik['keluhan_diajukan'] ?? 0))
@php($totalDenda = $statistik['total_denda'] ?? 0)
@php($totalPeminjaman = $statistik['total'] ?? ($aktif + $menunggu + $riwayatSelesai))
@php($persentaseSelesai = $totalPeminjaman > 0 ? number_format(($riwayatSelesai / $totalPeminjaman) * 100, 0) : 0)

@section('content')

{{-- ====== STYLE DASHBOARD MAHASISWA (LIGHT + DARK NEON + ANIMASI) ====== --}}
<style>
    :root {
        /* Light theme */
        --sipkam-bg-light: linear-gradient(135deg, #d7f3f4 0%, #e8f2ff 40%, #dde9fb 100%);
        --sipkam-card-light: rgba(255, 255, 255, 0.96);
        --sipkam-text-dark: #0f172a;
        --sipkam-text-muted: #64748b;

        /* Dark theme (hitam + neon hijau) */
        --sipkam-bg-dark: radial-gradient(circle at top, #020617 0%, #020617 40%, #020617 100%);
        --sipkam-card-dark: #020617;
        --sipkam-text-dark-mode: #e5e7eb;
        --sipkam-muted-dark: #9ca3af;
        --sipkam-accent-green: #22c55e;
        --sipkam-accent-pill: #bbf7d0;
    }

    /* ====== BODY THEME + GRADIENT ANIMASI ====== */
    body.sipkam-light {
        background: var(--sipkam-bg-light);
        color: var(--sipkam-text-dark);
        background-size: 300% 300%;
        animation: sipkamGradientLight 10s ease infinite; /* lebih cepat */
    }

    body.sipkam-dark {
        background: var(--sipkam-bg-dark);
        color: var(--sipkam-text-dark-mode);
        background-size: 200% 200%;
        animation: sipkamGradientDark 12s ease infinite; /* lebih cepat */
    }

    @keyframes sipkamGradientLight {
        0%   { background-position: 0% 0%; }
        50%  { background-position: 100% 100%; }
        100% { background-position: 0% 0%; }
    }

    @keyframes sipkamGradientDark {
        0%   { background-position: 50% 0%; }
        50%  { background-position: 50% 100%; }
        100% { background-position: 50% 0%; }
    }

    /* ====== WRAPPER DASHBOARD ====== */
    .sipkam-dashboard {
        position: relative;
        overflow: hidden; /* supaya animasi di belakang nggak keluar area */
        padding-top: 1.5rem;
        padding-bottom: 2.5rem;
    }

    /* ====== ANIMASI NEON PARTICLES (BINTANG) ====== */
    .sipkam-particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }

    .sipkam-particles span {
        position: absolute;
        width: 14px;
        height: 14px;
        border-radius: 999px;
        opacity: .7;
        animation: floatParticle 5.5s linear infinite; /* base lebih cepat */
    }

    /* warna partikel mengikuti tema */
    body.sipkam-light .sipkam-particles span {
        background: radial-gradient(circle,
            rgba(59, 130, 246, 0.95),
            rgba(59, 130, 246, 0.0) 60%);
        box-shadow: 0 0 18px rgba(59, 130, 246, 0.9); /* biru di mode terang */
    }

    body.sipkam-dark .sipkam-particles span {
        background: radial-gradient(circle,
            rgba(34, 197, 94, 0.95),
            rgba(34, 197, 94, 0.0) 60%);
        box-shadow: 0 0 18px rgba(34, 197, 94, 0.9); /* hijau neon di mode gelap */
    }

    /* posisi & kecepatan beda-beda biar hidup – ditambah jumlah bintang */
    .sipkam-particles span:nth-child(1)  { top: 10%; left: 18%; animation-duration: 4.5s; animation-delay: -1s; }
    .sipkam-particles span:nth-child(2)  { top: 25%; left: 70%; animation-duration: 5.2s; animation-delay: -2s; }
    .sipkam-particles span:nth-child(3)  { top: 60%; left: 15%; animation-duration: 5.8s; animation-delay: -3s; }
    .sipkam-particles span:nth-child(4)  { top: 75%; left: 55%; animation-duration: 4.8s; animation-delay: -4s; }
    .sipkam-particles span:nth-child(5)  { top: 40%; left: 40%; animation-duration: 6s;   animation-delay: -2.5s; }
    .sipkam-particles span:nth-child(6)  { top: 15%; left: 85%; animation-duration: 5.1s; animation-delay: -5s; }
    .sipkam-particles span:nth-child(7)  { top: 85%; left: 25%; animation-duration: 4.7s; animation-delay: -1.5s; }
    .sipkam-particles span:nth-child(8)  { top: 55%; left: 82%; animation-duration: 5.6s; animation-delay: -3.5s; }
    .sipkam-particles span:nth-child(9)  { top: 5%;  left: 45%; animation-duration: 4.3s; animation-delay: -2.2s; }
    .sipkam-particles span:nth-child(10) { top: 35%; left: 10%; animation-duration: 5.4s; animation-delay: -3.1s; }
    .sipkam-particles span:nth-child(11) { top: 65%; left: 65%; animation-duration: 4.9s; animation-delay: -1.8s; }
    .sipkam-particles span:nth-child(12) { top: 88%; left: 80%; animation-duration: 5.7s; animation-delay: -4.4s; }
    .sipkam-particles span:nth-child(13) { top: 20%; left: 30%; animation-duration: 4.6s; animation-delay: -2.9s; }
    .sipkam-particles span:nth-child(14) { top: 50%; left: 55%; animation-duration: 5.3s; animation-delay: -3.7s; }

    @keyframes floatParticle {
        0% {
            transform: translate3d(0, 0, 0) scale(0.9);
            opacity: 0.35;
        }
        50% {
            transform: translate3d(40px, -30px, 0) scale(1.25);
            opacity: 1;
        }
        100% {
            transform: translate3d(-25px, 40px, 0) scale(0.8);
            opacity: 0.25;
        }
    }

    /* semua konten dashboard di atas animasi */
    .sipkam-dashboard > *:not(.sipkam-particles) {
        position: relative;
        z-index: 5;
    }

    /* ====== HEADER ====== */
    .sipkam-dashboard .dashboard-header {
        border-radius: 20px;
        padding: 16px 24px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(20px);
    }

    body.sipkam-dark .sipkam-dashboard .dashboard-header {
        background: var(--sipkam-card-dark);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9);
        border: 1px solid rgba(34, 197, 94, 0.25);
    }

    /* ====== CARD UMUM ====== */
    .sipkam-dashboard .card-modern {
        border-radius: 18px;
        border: none;
        background: var(--sipkam-card-light);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(18px);
    }

    body.sipkam-dark .sipkam-dashboard .card-modern {
        background: var(--sipkam-card-dark);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9);
        border: 1px solid rgba(15, 23, 42, 0.9);
    }

    .sipkam-dashboard .card-header {
        border-bottom: none;
        background: transparent;
        padding-bottom: .5rem;
    }

    .sipkam-dashboard .card-body {
        padding-top: .9rem;
    }

    /* Saat dark mode, semua .text-dark & .text-muted di-adjust */
    body.sipkam-dark .sipkam-dashboard .text-dark {
        color: var(--sipkam-text-dark-mode) !important;
    }
    body.sipkam-dark .sipkam-dashboard .text-muted {
        color: var(--sipkam-muted-dark) !important;
    }

    /* ====== KARTU STATISTIK ====== */
    .stat-card {
        background: var(--sipkam-card-light) !important;
        color: var(--sipkam-text-dark);
    }

    .stat-card .label {
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--sipkam-text-muted);
    }

    .stat-card .value {
        font-size: 1.9rem;
        font-weight: 700;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }

    .stat-pill-aktif {
        background: rgba(37, 99, 235, .1);
        color: #1d4ed8;
    }

    .stat-pill-menunggu {
        background: rgba(245, 158, 11, .12);
        color: #b45309;
    }

    .stat-pill-selesai {
        background: rgba(34, 197, 94, .12);
        color: #15803d;
    }

    body.sipkam-dark .stat-card {
        background: #020617 !important;
        color: var(--sipkam-text-dark-mode);
        border: 1px solid rgba(55, 65, 81, 0.9);
    }
    body.sipkam-dark .stat-card .label {
        color: var(--sipkam-muted-dark);
    }
    body.sipkam-dark .stat-card .value {
        color: #f9fafb;
    }
    body.sipkam-dark .stat-pill-aktif,
    body.sipkam-dark .stat-pill-menunggu,
    body.sipkam-dark .stat-pill-selesai {
        background: rgba(34, 197, 94, 0.14);
        color: var(--sipkam-accent-pill);
    }

    /* ====== STATISTIK REKAP ====== */
    .stat-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }
    .stat-overview .item {
        padding: 12px 14px;
        border-radius: 14px;
        background: rgba(248, 250, 252, 0.98);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }
    .stat-overview .label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--sipkam-text-muted);
        margin-bottom: 4px;
        display: block;
    }
    .stat-overview .value {
        font-size: 1.2rem;
        font-weight: 700;
    }
    body.sipkam-dark .stat-overview .item {
        background: #020617;
        border: 1px solid rgba(31, 41, 55, 0.95);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.8);
    }

    /* ====== MINI CARD / SHORTCUT ====== */
    .shortcut-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .shortcut-grid.status {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    @media (min-width: 992px) {
        .shortcut-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .shortcut-grid.status {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    .shortcut-card {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 14px;
        text-decoration: none !important;
        background: rgba(248, 250, 252, 0.98);
        color: var(--sipkam-text-dark);
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .shortcut-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        background: #ffffff;
    }

    .shortcut-icon {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: .9rem;
    }

    .shortcut-icon-primary   { background: #e0f2fe; color: #0f6efc; }
    .shortcut-icon-warning   { background: #fef3c7; color: #d97706; }
    .shortcut-icon-info      { background: #cffafe; color: #0891b2; }
    .shortcut-icon-success   { background: #dcfce7; color: #16a34a; }
    .shortcut-icon-secondary { background: #e5e7eb; color: #4b5563; }
    .shortcut-icon-dark      { background: #111827; color: #f9fafb; }

    .shortcut-label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--sipkam-text-muted);
        margin-bottom: 1px;
    }

    .shortcut-title {
        font-size: .9rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    body.sipkam-dark .shortcut-card {
        background: #020617;
        color: var(--sipkam-text-dark-mode);
        border: 1px solid rgba(31, 41, 55, 0.95);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.8);
    }

    body.sipkam-dark .shortcut-label {
        color: var(--sipkam-muted-dark);
    }

    body.sipkam-dark .shortcut-icon-primary   { background: rgba(37, 99, 235, .18); color: #93c5fd; }
    body.sipkam-dark .shortcut-icon-warning   { background: rgba(252, 211, 77, .2);  color: #fde68a; }
    body.sipkam-dark .shortcut-icon-info      { background: rgba(56, 189, 248, .18); color: #bae6fd; }
    body.sipkam-dark .shortcut-icon-success   { background: rgba(34, 197, 94, .2);  color: #bbf7d0; }
    body.sipkam-dark .shortcut-icon-secondary { background: rgba(75, 85, 99, .7);  color: #e5e7eb; }
    body.sipkam-dark .shortcut-icon-dark      { background: #111827;              color: #f9fafb; }

    /* ====== LIST BARANG DIPINJAM ====== */
    .peminjaman-item {
        border-radius: 16px;
        border: none;
        background: rgba(248, 250, 252, 0.98);
    }
    .peminjaman-item small {
        color: var(--sipkam-text-muted);
    }
    body.sipkam-dark .peminjaman-item {
        background: #020617;
        border: 1px solid rgba(31, 41, 55, 0.95);
    }
    body.sipkam-dark .peminjaman-item small {
        color: var(--sipkam-muted-dark);
    }

    /* ====== AKTIVITAS ====== */
    .aktivitas-item {
        border-radius: 14px;
        background: rgba(248, 250, 252, 0.98);
    }
    body.sipkam-dark .aktivitas-item {
        background: #020617;
        border: 1px solid rgba(31, 41, 55, 0.95);
    }

    /* ====== TOGGLE TEMA ====== */
    .theme-toggle-btn {
        border-radius: 999px;
        font-size: .8rem;
        padding: 6px 12px;
        border: none;
        background: rgba(15, 23, 42, 0.06);
        color: var(--sipkam-text-dark);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    body.sipkam-dark .theme-toggle-btn {
        background: #020617;
        color: var(--sipkam-text-dark-mode);
        border: 1px solid rgba(55, 65, 81, 0.9);
    }

    /* ====== AKSEN TEKS HIJAU DI MODE GELAP ====== */
    body.sipkam-dark .sipkam-dashboard h1,
    body.sipkam-dark .sipkam-dashboard h4,
    body.sipkam-dark .sipkam-dashboard h5,
    body.sipkam-dark .sipkam-dashboard .fw-bold {
        color: var(--sipkam-accent-green) !important;
    }

    body.sipkam-dark .sipkam-dashboard .label,
    body.sipkam-dark .sipkam-dashboard .shortcut-title {
        color: var(--sipkam-accent-green) !important;
    }

    body.sipkam-dark .sipkam-dashboard a:not(.btn) {
        color: var(--sipkam-accent-green) !important;
    }

    @media (max-width: 767.98px) {
        .sipkam-dashboard { padding-top: 1rem; }
        .sipkam-dashboard .dashboard-header { padding: 12px 16px; }
        .stat-card .value { font-size: 1.6rem; }
    }
</style>

<div class="container-fluid sipkam-dashboard">
    {{-- LAYER ANIMASI NEON PARTICLES --}}
    <div class="sipkam-particles">
        {{-- total 14 bintang --}}
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
        <span></span><span></span>
    </div>

    {{-- HEADER --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <small class="text-muted d-block mb-1">
                Selamat datang di SIPKAM {{ auth()->user()->nama ?? '' }}
            </small>

            <h1 class="h4 mb-1 fw-bold">Dashboard Mahasiswa</h1>
            <p class="text-muted mb-0 small">Ringkasan aktivitas peminjaman pribadi Anda</p>
        </div>
        <div class="text-end">
            <small class="d-block" id="current-date">{{ now()->translatedFormat('l, d F Y') }}</small>
            <small class="d-block" id="current-time">{{ now()->format('H:i') }}</small>
            <button id="theme-toggle" class="theme-toggle-btn mt-2">
                <i id="theme-toggle-icon" class="fas fa-sun"></i>
                <span id="theme-toggle-text">Mode terang</span>
            </button>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-modern h-100 border-0 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="label">Riwayat Selesai</span>
                            <span class="stat-pill stat-pill-selesai">Selesai</span>
                        </div>
                        <div class="value">{{ $riwayatSelesai }}</div>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-check-double fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-modern h-100 border-0 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="label">Total Peminjaman</span>
                            <span class="stat-pill stat-pill-aktif">Total</span>
                        </div>
                        <div class="value">{{ $totalPeminjaman }}</div>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-hand-holding fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-modern h-100 border-0 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="label">Keluhan Diajukan</span>
                            <span class="stat-pill stat-pill-menunggu">Keluhan</span>
                        </div>
                        <div class="value">{{ $keluhanDiajukan }}</div>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-comments fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-modern h-100 border-0 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="label">Total Denda</span>
                            <span class="stat-pill stat-pill-selesai">IDR</span>
                        </div>
                        <div class="value">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-wallet fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- REKAP STATISTIK --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-modern h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-chart-pie me-2"></i>Rekap Statistik
                        </h5>
                        <small class="text-muted">Ringkasan total peminjaman, keluhan, dan denda</small>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Total peminjaman</small>
                        <span class="fw-bold h5 mb-0">{{ $totalPeminjaman }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="stat-overview mb-3">
                        <div class="item">
                            <span class="label">Riwayat selesai</span>
                            <span class="value text-success">{{ $riwayatSelesai }}</span>
                        </div>
                        <div class="item">
                            <span class="label">Total peminjaman</span>
                            <span class="value text-primary">{{ $totalPeminjaman }}</span>
                        </div>
                        <div class="item">
                            <span class="label">Keluhan diajukan</span>
                            <span class="value text-warning">{{ $keluhanDiajukan }}</span>
                        </div>
                        <div class="item">
                            <span class="label">Total denda</span>
                            <span class="value text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Progress penyelesaian</small>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseSelesai }}%;" aria-valuenow="{{ $persentaseSelesai }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARANG DIPINJAM & AKTIVITAS --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-box me-2"></i>Barang yang Sedang Dipinjam
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($barangDipinjam as $peminjaman)
                        <div class="d-flex align-items-center mb-3 p-3 peminjaman-item">
                            @if(optional($peminjaman->barang)->foto_url)
                                <img src="{{ $peminjaman->barang->foto_url }}" alt="Foto {{ $peminjaman->barang->nama_barang }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $peminjaman->barang->nama_barang ?? '-' }}</h6>
                                <small class="d-block">
                                    Kode: {{ $peminjaman->barang->kode_barang ?? '-' }} |
                                    Kategori: {{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}
                                </small>
                                <small class="d-block">
                                    Pinjam: {{ \Carbon\Carbon::parse($peminjaman->waktu_awal)->format('d M Y H:i') }} |
                                    Kembali: {{ \Carbon\Carbon::parse($peminjaman->waktu_akhir)->format('d M Y H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-success">{{ $peminjaman->status }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                            <p>Anda belum memiliki barang yang sedang dipinjam.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-line me-2"></i>Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($aktivitas as $item)
                        <div class="mb-3 aktivitas-item p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <strong class="d-block">{{ $item['judul'] }}</strong>
                                    <small class="text-muted">{{ $item['deskripsi'] }}</small>
                                </div>
                                <small class="text-muted ms-2">{{ $item['waktu'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                            <p>Belum ada aktivitas terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT: WAKTU REAL TIME + THEME TOGGLE --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateEl = document.getElementById('current-date');
        const timeEl = document.getElementById('current-time');
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');
        const themeToggleText = document.getElementById('theme-toggle-text');

        function updateTime() {
            const now = new Date();
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('id-ID', {
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }
        updateTime();
        setInterval(updateTime, 1000);

        function applyTheme(theme) {
            document.body.classList.remove('sipkam-light', 'sipkam-dark');
            document.body.classList.add(theme);

            if (theme === 'sipkam-dark') {
                if (themeToggleIcon) {
                    themeToggleIcon.classList.remove('fa-sun');
                    themeToggleIcon.classList.add('fa-moon');
                }
                if (themeToggleText) themeToggleText.textContent = 'Mode gelap';
            } else {
                if (themeToggleIcon) {
                    themeToggleIcon.classList.remove('fa-moon');
                    themeToggleIcon.classList.add('fa-sun');
                }
                if (themeToggleText) themeToggleText.textContent = 'Mode terang';
            }
        }

        const savedTheme = localStorage.getItem('sipkam-theme');
        const prefersDark = window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (prefersDark ? 'sipkam-dark' : 'sipkam-light');
        applyTheme(initialTheme);

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function () {
                const isDark = document.body.classList.contains('sipkam-dark');
                const newTheme = isDark ? 'sipkam-light' : 'sipkam-dark';
                applyTheme(newTheme);
                localStorage.setItem('sipkam-theme', newTheme);
            });
        }
    });
</script>

@endsection
