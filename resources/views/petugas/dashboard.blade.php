@extends('layouts.app')

@section('content')

{{-- ===== STYLE KHUSUS DASHBOARD PETUGAS (PALET HIJAU–TEAL) ===== --}}
<style>
    :root {
        --sipkam-admin-primary: #2C6975;
        --sipkam-admin-primary-soft: #6BB2A0;
        --sipkam-admin-surface: #E0ECDE;
        --sipkam-admin-surface-soft: #CDE0C9;
        --sipkam-admin-bg: #DAF1DE;
    }

    .sipkam-admin-dashboard {
        min-height: 100vh;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        background: linear-gradient(180deg, #DAF1DE 0%, #E0ECDE 40%, #CDE0C9 100%);
    }

    body.sipkam-dark .sipkam-admin-dashboard {
        background: radial-gradient(circle at top, #051F20 0%, #0B2B26 40%, #163832 100%);
    }

    .sipkam-header-card {
        border-radius: 18px;
        background: rgba(255,255,255,0.96);
        box-shadow: 0 18px 40px rgba(44,105,117,0.18);
        border: 1px solid rgba(205,224,201,0.9);
    }

    body.sipkam-dark .sipkam-header-card {
        background: #051F20;
        border-color: #163832;
        box-shadow: 0 22px 60px rgba(0,0,0,0.9);
    }

    body.sipkam-dark .sipkam-admin-dashboard .text-dark {
        color: #E0ECDE !important;
    }

    body.sipkam-dark .sipkam-admin-dashboard .text-muted {
        color: #8EB69B !important;
    }

    /* ====== STAT CARDS ====== */
    .sipkam-stat-primary {
        background: linear-gradient(135deg, #2C6975 0%, #6BB2A0 100%);
        color: #FFFFFF;
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(44,105,117,0.45);
        border: none;
    }

    .sipkam-stat-soft {
        border-radius: 18px;
        background: #E0ECDE;
        box-shadow: 0 14px 30px rgba(148,163,184,0.28);
        border: 1px solid rgba(205,224,201,0.85);
    }

    .sipkam-stat-soft-yellow {
        border-radius: 18px;
        background: #FFF8E1;
        box-shadow: 0 14px 30px rgba(251,191,36,0.25);
        border: 1px solid rgba(252,211,77,0.6);
    }

    .sipkam-stat-soft-red {
        border-radius: 18px;
        background: #FEE2E2;
        box-shadow: 0 14px 30px rgba(248,113,113,0.25);
        border: 1px solid rgba(254,202,202,0.9);
    }

    body.sipkam-dark .sipkam-stat-soft,
    body.sipkam-dark .sipkam-stat-soft-yellow,
    body.sipkam-dark .sipkam-stat-soft-red {
        background: #0B2B26;
        border-color: #163832;
        box-shadow: 0 22px 50px rgba(0,0,0,0.85);
    }

    /* ====== CHIP BUTTONS (grafik kiri) ====== */
    .sipkam-chip-primary {
        border-radius: 999px;
        border: none;
        background: linear-gradient(135deg, #2C6975, #6BB2A0);
        color: #FFFFFF;
        box-shadow: 0 12px 32px rgba(44,105,117,0.55);
        font-size: 0.9rem;
    }

    .sipkam-chip-outline {
        border-radius: 999px;
        border: 1px solid #2C6975;
        color: #2C6975;
        background: transparent;
        font-size: 0.9rem;
    }

    body.sipkam-dark .sipkam-chip-outline {
        border-color: #8EB69B;
        color: #E0ECDE;
    }

    /* ====== RANGE PILLS (7 hari, 1 bulan, dll.) ====== */
    .sipkam-range-pill {
        border-radius: 999px;
        padding: 0.15rem 0.7rem;
        font-size: 0.75rem;
        border: 1px solid rgba(44,105,117,0.35);
        color: #2C6975;
        background: rgba(255,255,255,0.85);
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }

    .sipkam-range-pill:hover {
        background: rgba(44,105,117,0.1);
    }

    .sipkam-range-pill-active {
        background: linear-gradient(135deg, #2C6975, #6BB2A0);
        color: #FFFFFF;
        border-color: transparent;
        box-shadow: 0 8px 24px rgba(44,105,117,0.45);
    }

    body.sipkam-dark .sipkam-range-pill {
        border-color: #163832;
        background: #051F20;
        color: #E0ECDE;
    }

    body.sipkam-dark .sipkam-range-pill-active {
        background: linear-gradient(135deg, #2C6975, #6BB2A0);
        color: #FFFFFF;
        box-shadow: 0 10px 30px rgba(0,0,0,0.9);
    }

    /* ====== MINI LINE CHART (Chart.js container) ====== */
    .sipkam-mini-chart {
    margin-top: 0.75rem;
    padding-bottom: 0.75rem;   /* sedikit ruang bawah agar kotak terasa penuh */
}

.sipkam-mini-chart-line {
    position: relative;
    height: 190px;             /* <— tingginya diperbesar dari 130px */
}

    .sipkam-mini-chart-legend {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        margin-top: 0.4rem;
        color: #64748b;
    }

    body.sipkam-dark .sipkam-mini-chart-legend {
        color: #8EB69B;
    }

    /* ====== KELUHAN CARD ====== */
    .sipkam-keluhan-card .sipkam-keluhan-body {
        padding: 0.9rem 1.25rem 1rem;
    }

    .sipkam-keluhan-actions .sipkam-keluhan-btn-primary,
    .sipkam-keluhan-actions .sipkam-keluhan-btn-soft {
        display: inline-flex;
        align-items: center;
        border-radius: 0.6rem;
        padding: 0.35rem 0.9rem;
        font-size: 0.85rem;
        box-shadow: none;
        border: none;
    }

    .sipkam-keluhan-btn-primary {
        background: linear-gradient(135deg, #2C6975, #6BB2A0);
        color: #FFFFFF;
    }

    .sipkam-keluhan-btn-soft {
        background: #CDE0C9;
        color: #234e52;
    }

    body.sipkam-dark .sipkam-keluhan-btn-soft {
        background: #163832;
        color: #E0ECDE;
    }

    .sipkam-keluhan-actions i {
        font-size: 0.8rem;
    }

    .sipkam-admin-dashboard .card {
        border-radius: 18px;
    }

    body.sipkam-dark .sipkam-admin-dashboard .card {
        background: #051F20;
        border-color: #163832;
        box-shadow: 0 22px 60px rgba(0,0,0,0.9);
    }

    @media (max-width: 767.98px) {
        .sipkam-admin-dashboard {
            padding-inline: 1rem;
        }
    }
</style>

@php
    $chartRange = $chartRange ?? '7hari';
@endphp

<div class="container-fluid sipkam-admin-dashboard">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-4 sipkam-header-card">
        <div class="card-body d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h4 mb-1 fw-bold text-dark">Dashboard Petugas</h1>
                <p class="text-muted mb-0">Selamat datang di panel administrasi sistem peminjaman</p>
            </div>
            <div class="text-end">
                <small id="dashboard-date" class="text-muted d-block">
                    {{ now()->format('l, d F Y') }}
                </small>
                <small id="dashboard-time" class="text-muted">
                    {{ now()->format('H:i') }}
                </small>
            </div>
        </div>
    </div>

    {{-- ===== BARIS 1: STATISTIK ===== --}}
    <div class="row g-3 mb-4">
        {{-- KIRI: Total Barang --}}
        <div class="col-xl-5 col-lg-6">
            <div class="card h-100 border-0 shadow-sm sipkam-stat-primary">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 opacity-75">Total Barang</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalBarang ?? 0 }}</h2>
                    </div>
                    <div class="ms-3">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center"
                             style="width:56px;height:56px;">
                            <i class="fas fa-box fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: 3 kartu kecil --}}
        <div class="col-xl-7 col-lg-6">
            <div class="row g-3 h-100">
                {{-- Barang Tersedia --}}
                <div class="col-md-4 col-12">
                    <div class="card h-100 border-0 shadow-sm sipkam-stat-soft">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-muted">Barang Tersedia</h6>
                                <h2 class="mb-0 fw-bold text-dark">{{ $barangTersedia ?? 0 }}</h2>
                            </div>
                            <div class="ms-3">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;">
                                    <i class="fas fa-check-circle fa-lg text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Peminjaman Aktif --}}
                <div class="col-md-4 col-12">
                    <div class="card h-100 border-0 shadow-sm sipkam-stat-soft-yellow">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-muted">Peminjaman Aktif</h6>
                                <h2 class="mb-0 fw-bold text-dark">{{ $peminjamanAktif ?? 0 }}</h2>
                            </div>
                            <div class="ms-3">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;">
                                    <i class="fas fa-hand-holding fa-lg text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Denda Belum Dibayar --}}
                <div class="col-md-4 col-12">
                    <div class="card h-100 border-0 shadow-sm sipkam-stat-soft-red">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-muted">Denda Belum Dibayar</h6>
                                <h2 class="mb-0 fw-bold text-dark">{{ $dendaBelumDibayar ?? 0 }}</h2>
                            </div>
                            <div class="ms-3">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;">
                                    <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== BARIS 2: GRAFIK (KIRI) + KELUHAN & STATUS (KANAN) ===== --}}
    <div class="row g-3 mb-4">
        {{-- KIRI: Grafik Transaksi & Statistik Barang --}}
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">
                                Grafik Transaksi &amp; Statistik Barang
                            </h5>
                            <p class="text-muted small mb-0">
                                Ringkasan peminjaman, pengembalian, dan ketersediaan barang.
                            </p>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1 mt-2 mt-sm-0">
                            <div class="d-flex flex-wrap gap-1 justify-content-end">
                                <a href="{{ route('petugas.dashboard', ['range' => '7hari']) }}"
                                   class="sipkam-range-pill {{ $chartRange === '7hari' ? 'sipkam-range-pill-active' : '' }}">
                                    7 Hari
                                </a>
                                <a href="{{ route('petugas.dashboard', ['range' => '1bulan']) }}"
                                   class="sipkam-range-pill {{ $chartRange === '1bulan' ? 'sipkam-range-pill-active' : '' }}">
                                    1 Bulan
                                </a>
                                <a href="{{ route('petugas.dashboard', ['range' => '3bulan']) }}"
                                   class="sipkam-range-pill {{ $chartRange === '3bulan' ? 'sipkam-range-pill-active' : '' }}">
                                    3 Bulan
                                </a>
                                <a href="{{ route('petugas.dashboard', ['range' => '1tahun']) }}"
                                   class="sipkam-range-pill {{ $chartRange === '1tahun' ? 'sipkam-range-pill-active' : '' }}">
                                    1 Tahun
                                </a>
                                <a href="{{ route('petugas.dashboard', ['range' => 'semua']) }}"
                                   class="sipkam-range-pill {{ $chartRange === 'semua' ? 'sipkam-range-pill-active' : '' }}">
                                    Semua
                                </a>
                            </div>
                            <a href="{{ route('petugas.kategori.index') }}"
                               class="small text-decoration-none text-muted">
                                <i class="fas fa-tags me-1"></i>Kelola Kategori
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-start align-items-center mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('barang.index') }}"
                               class="btn sipkam-chip-primary d-flex align-items-center px-3">
                                <i class="fas fa-list me-2"></i>
                                Lihat Barang
                            </a>
                            <a href="{{ route('petugas.barang.create') }}"
                               class="btn sipkam-chip-outline d-flex align-items-center px-3">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Barang
                            </a>
                        </div>
                    </div>

                    {{-- MINI LINE CHART CRUD (Chart.js) --}}
                    <div class="sipkam-mini-chart">
                        <div class="sipkam-mini-chart-line">
                            <canvas id="transaksiChart"></canvas>
                        </div>

                        <div class="sipkam-mini-chart-legend">
                            <span>Peminjaman</span>
                            <span>Pengembalian</span>
                            <span>Ketersediaan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Keluhan + Status Sistem (versi 1) --}}
        <div class="col-xl-5">
            <div class="row g-3 h-100">
                {{-- Keluhan --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm sipkam-keluhan-card h-100">
                        <div class="card-body sipkam-keluhan-body">
                            <h5 class="fw-bold mb-2 text-dark">
                                Keluhan
                            </h5>
                            <p class="text-muted small mb-3">
                                Pantau dan kelola keluhan selama proses peminjaman maupun kendala service barang.
                            </p>

                            <div class="sipkam-keluhan-actions d-flex flex-wrap gap-2">
                                <a href="{{ route('petugas.perpanjangan.index') }}"
                                   class="btn sipkam-keluhan-btn-primary">
                                    <i class="fas fa-flag me-2"></i>
                                    Keluhan Peminjaman
                                </a>

                                <a href="{{ route('petugas.service.index') }}"
                                   class="btn sipkam-keluhan-btn-soft">
                                    <i class="fas fa-tools me-2"></i>
                                    Kelola Service
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Sistem (versi 1) --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 text-dark">
                                <i class="fas fa-calendar-check me-2"></i>Status Sistem
                            </h5>

                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-circle text-success me-3"></i>
                                <div>
                                    <div class="fw-semibold">Sistem Aktif</div>
                                    <small class="text-muted">Semua layanan berjalan normal</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-circle text-info me-3"></i>
                                <div>
                                    <div class="fw-semibold">Database</div>
                                    <small class="text-muted">Koneksi stabil</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="fas fa-circle text-warning me-3"></i>
                                <div>
                                    <div class="fw-semibold">Backup</div>
                                    <small class="text-muted">
                                        Terakhir: {{ now()->subDay()->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== BARIS 3: Aktivitas Terbaru (FULL LEBAR) ===== --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="fas fa-chart-line me-2"></i>Aktivitas Terbaru
                    </h5>
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div class="p-3">
                                <i class="fas fa-users fa-lg text-primary mb-2"></i>
                                <h4 class="mb-1 fw-bold">{{ $totalPengguna ?? 0 }}</h4>
                                <small class="text-muted">Total Pengguna</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div class="p-3">
                                <i class="fas fa-exclamation-triangle fa-lg text-danger mb-2"></i>
                                <h4 class="mb-1 fw-bold">{{ $totalKeluhan ?? 0 }}</h4>
                                <small class="text-muted">Keluhan Aktif</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3">
                                <i class="fas fa-undo fa-lg text-success mb-2"></i>
                                <h4 class="mb-1 fw-bold">{{ $pengembalianHariIni ?? 0 }}</h4>
                                <small class="text-muted">Pengembalian Hari Ini</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3">
                                <i class="fas fa-bell fa-lg text-warning mb-2"></i>
                                <h4 class="mb-1 fw-bold">{{ $notifikasiBelumDibaca ?? 0 }}</h4>
                                <small class="text-muted">Notifikasi Baru</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- === SCRIPT: Chart.js + Jam/Tanggal === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateEl = document.getElementById('dashboard-date');
        const timeEl = document.getElementById('dashboard-time');

        function updateDateTime() {
            const now = new Date();

            const dateOptions = {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            dateEl.textContent = now.toLocaleDateString('id-ID', dateOptions);

            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            timeEl.textContent = now.toLocaleTimeString('id-ID', timeOptions);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // ====== GRAFIK TRANSAKSI (CRUD) ======
        const ctx = document.getElementById('transaksiChart');
        if (ctx) {
            const labels           = @json($chartLabels       ?? []);
            const dataPeminjaman   = @json($chartPeminjaman   ?? []);
            const dataPengembalian = @json($chartPengembalian ?? []);
            const dataKetersediaan = @json($chartKetersediaan ?? []);

            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Peminjaman',
                            data: dataPeminjaman,
                            borderColor: '#2C6975',
                            backgroundColor: 'rgba(44,105,117,0.18)',
                            borderWidth: 2,
                            tension: 0.35,
                            pointRadius: 3,
                            pointBackgroundColor: '#2C6975'
                        },
                        {
                            label: 'Pengembalian',
                            data: dataPengembalian,
                            borderColor: '#6BB2A0',
                            backgroundColor: 'rgba(107,178,160,0.15)',
                            borderWidth: 2,
                            tension: 0.35,
                            pointRadius: 3,
                            pointBackgroundColor: '#6BB2A0'
                        },
                        {
                            label: 'Ketersediaan',
                            data: dataKetersediaan,
                            borderColor: '#CDE0C9',
                            backgroundColor: 'rgba(205,224,201,0.10)',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            tension: 0.25,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(148,163,184,0.35)'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
