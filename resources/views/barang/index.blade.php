@extends('layouts.app')

@php
    $isPetugasView = request()->routeIs('barang.index')
        && auth()->check()
        && auth()->user()->role === 'petugas';
@endphp

@section('content')

{{-- ======== STYLE KHUSUS HALAMAN MANAJEMEN BARANG (TEMA HIJAU) ======== --}}
<style>
    :root {
        --sipkam-deep-1: #051F20;
        --sipkam-deep-2: #0B2B26;
        --sipkam-deep-3: #163832;
        --sipkam-deep-4: #235347;
        --sipkam-soft-1: #8EB69B;
        --sipkam-soft-2: #DAF1DE;
    }

    .sipkam-inventory-page {
        min-height: 100vh;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        background-color: #ffffff;
        background-image: none;
    }

    body.sipkam-dark .sipkam-inventory-page {
        background-color: #ffffff;
    }

    /* HEADER ATAS (breadcrumb + judul) */
    .sipkam-inventory-header {
        border-radius: 18px;
        background: linear-gradient(135deg, var(--sipkam-deep-1), var(--sipkam-deep-3));
        color: #ECFFF5;
        padding: 1.25rem 1.75rem;
        box-shadow: 0 20px 45px rgba(5, 31, 32, 0.65);
        border: 1px solid rgba(222, 247, 231, 0.7);
    }

    .sipkam-inventory-header .text-muted {
        color: rgba(218, 241, 222, 0.75) !important;
    }

    .sipkam-inventory-header h1,
    .sipkam-inventory-header .fw-bold {
        color: #ffffff !important;
    }

    .sipkam-inventory-header small {
        color: rgba(218, 241, 222, 0.9) !important;
    }

    /* KARTU UTAMA TABEL */
    .sipkam-card-inventory {
        border-radius: 20px;
        border: 1px solid rgba(222, 247, 231, 0.85);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 20px 50px rgba(3, 26, 23, 0.15);
        overflow: hidden;
    }

    body.sipkam-dark .sipkam-card-inventory {
        background: #051F20;
        border-color: #163832;
        box-shadow: 0 26px 70px rgba(0, 0, 0, 0.95);
    }

    /* HEADER FILTER */
    .sipkam-filter-header {
        background: linear-gradient(135deg, rgba(218,241,222,0.95), rgba(142,182,155,0.9));
        padding: 1.15rem 1.5rem 0.75rem;
    }

    body.sipkam-dark .sipkam-filter-header {
        background: linear-gradient(135deg, #051F20, #163832);
    }

    .sipkam-filter-header .form-control,
    .sipkam-filter-header .form-select {
        border-radius: 999px;
        border: 1px solid rgba(35, 83, 71, 0.28);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 6px 18px rgba(5, 31, 32, 0.08);
        font-size: 0.85rem;
    }

    .sipkam-filter-header .form-control:focus,
    .sipkam-filter-header .form-select:focus {
        border-color: var(--sipkam-deep-4);
        box-shadow: 0 0 0 2px rgba(142,182,155,0.75);
    }

    .sipkam-filter-header .input-group-text {
        border-radius: 999px 0 0 999px;
        border: 1px solid rgba(35, 83, 71, 0.15);
        background: rgba(255, 255, 255, 0.9);
    }

    /* BUTTON UTAMA DAN OUTLINE */
    .btn-sipkam-primary {
        border-radius: 999px;
        border: none;
        background: linear-gradient(135deg, var(--sipkam-deep-2), var(--sipkam-deep-4));
        color: #ECFFF5;
        font-weight: 600;
        padding-inline: 1.5rem;
        box-shadow: 0 14px 30px rgba(5, 31, 32, 0.6);
        transition: all 0.2s ease-in-out;
    }

    .btn-sipkam-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 20px 40px rgba(5, 31, 32, 0.75);
        color: #ffffff;
    }

    .btn-sipkam-outline {
        border-radius: 999px;
        border: 1px solid rgba(35, 83, 71, 0.5);
        background: rgba(255, 255, 255, 0.95);
        color: var(--sipkam-deep-4);
        font-weight: 500;
        box-shadow: 0 6px 16px rgba(3, 26, 23, 0.12);
        transition: all 0.2s ease-in-out;
    }

    .btn-sipkam-outline:hover {
        background: linear-gradient(135deg, var(--sipkam-soft-2), var(--sipkam-soft-1));
        color: var(--sipkam-deep-1);
        border-color: transparent;
    }

    /* TABEL BARANG */
    .sipkam-table-inventory thead th {
        background: linear-gradient(180deg, var(--sipkam-deep-2), var(--sipkam-deep-3));
        color: #ECFFF5;
        border: 0;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .sipkam-table-inventory tbody tr {
        transition: background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
    }

    .sipkam-table-inventory tbody tr:hover {
        background: rgba(218, 241, 222, 0.85);
        transform: translateY(-1px);
        box-shadow: 0 10px 28px rgba(5, 31, 32, 0.16);
    }

    .sipkam-table-inventory tbody td {
        border-color: rgba(203, 213, 225, 0.6);
    }

    .sipkam-table-inventory .badge.bg-light.text-dark {
        background: rgba(5, 31, 32, 0.06) !important;
        border-radius: 999px;
        border: 1px solid rgba(35, 83, 71, 0.22);
        padding-inline: 0.8rem;
        font-weight: 600;
        color: var(--sipkam-deep-3) !important;
        font-size: 0.78rem;
    }

    /* STATUS BADGE sedikit lembut */
    .sipkam-table-inventory .badge.bg-success {
        background: linear-gradient(135deg, #16a34a, var(--sipkam-soft-1)) !important;
        border-radius: 999px;
        padding-inline: 0.9rem;
        font-weight: 600;
    }

    .sipkam-table-inventory .badge.bg-warning {
        border-radius: 999px;
        font-weight: 600;
    }

    .sipkam-table-inventory .badge.bg-info,
    .sipkam-table-inventory .badge.bg-secondary,
    .sipkam-table-inventory .badge.bg-danger {
        border-radius: 999px;
        font-weight: 600;
    }

    /* FOOTER KETERANGAN */
    .sipkam-card-inventory .card-footer {
        background: linear-gradient(90deg, rgba(218,241,222,0.95), rgba(142,182,155,0.85));
        border-top: 1px solid rgba(35, 83, 71, 0.18);
        color: var(--sipkam-deep-3);
    }

    body.sipkam-dark .sipkam-card-inventory .card-footer {
        background: linear-gradient(90deg, #051F20, #163832);
        color: #E0F7EA;
        border-top-color: #163832;
    }

    /* FOTO KOSONG */
    .sipkam-empty-photo {
        background: linear-gradient(135deg, rgba(5,31,32,0.06), rgba(142,182,155,0.35));
        color: rgba(15, 23, 42, 0.35);
    }

    /* AKSI BUTTON GROUP */
    .sipkam-btn-group .btn {
        border-radius: 999px !important;
        font-size: 0.8rem;
        padding-inline: 0.9rem;
    }

    .sipkam-quick-stock button {
        border-radius: 999px;
        font-weight: 600;
        line-height: 1;
    }

    /* CARD BARANG UNTUK MAHASISWA */
    .sipkam-card-item {
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.97);
        border: 1px solid rgba(222, 247, 231, 0.8);
        box-shadow: 0 16px 36px rgba(3, 26, 23, 0.16);
        overflow: hidden;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .sipkam-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 50px rgba(3, 26, 23, 0.25);
    }

    .sipkam-card-item .btn-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--sipkam-deep-2), var(--sipkam-deep-4));
        border: none;
        box-shadow: 0 10px 26px rgba(5,31,32,0.55);
    }

    .sipkam-card-item .btn-primary:hover {
        box-shadow: 0 16px 40px rgba(5,31,32,0.8);
    }

    @media (max-width: 767.98px) {
        .sipkam-inventory-header {
            padding: 1rem 1.25rem;
        }
    }
</style>

<div class="container-fluid sipkam-inventory-page">

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if($isPetugasView)

        {{-- === OVERRIDE BACKGROUND BODY KHUSUS HALAMAN BARANG PETUGAS === --}}
        <style>
            body.sipkam-light {
                background: linear-gradient(
                    180deg,
                    var(--sipkam-soft-2) 0%,
                    #E5F3E7 40%,
                    var(--sipkam-soft-1) 100%
                ) !important;
                background-attachment: fixed;
            }
        </style>

        {{-- ====== VIEW PETUGAS: TABEL MANAJEMEN BARANG ====== --}}

        {{-- HEADER ATAS --}}
        <div class="sipkam-inventory-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted small mb-1">
                    Dashboard /
                    <span class="fw-semibold">Manajemen Barang</span>
                </p>
                <h1 class="h4 mb-1 fw-bold">Manajemen Barang</h1>
                <small>
                    Kelola stok, status, dan detail barang inventaris kampus
                </small>
            </div>
            <a href="{{ route('petugas.barang.create') }}"
               class="btn btn-sipkam-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Tambah Barang
            </a>
        </div>

        {{-- KARTU TABEL --}}
        <div class="card sipkam-card-inventory border-0">
            <div class="card-header border-0 pb-0 sipkam-filter-header">
                <form method="GET" action="{{ route('barang.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                   name="q"
                                   class="form-control shadow-none"
                                   placeholder="Cari nama / kode barang"
                                   value="{{ request('q') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        @php $selectedStatus = request('status'); @endphp
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua status</option>
                            <option value="tersedia" @selected($selectedStatus === 'tersedia')>Tersedia</option>
                            <option value="dipinjam" @selected($selectedStatus === 'dipinjam')>Sedang dipinjam</option>
                            <option value="dalam_service" @selected($selectedStatus === 'dalam_service')>Sedang service</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-sm btn-sipkam-outline w-100" type="submit">
                            Filter
                        </button>
                    </div>

                    <div class="col-md-3 text-md-end small text-muted mt-2 mt-md-0">
                        Total barang: <span class="fw-semibold">{{ $barang->count() }}</span>
                    </div>
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle mb-0 sipkam-table-inventory">
                    <thead>
                        <tr>
                            <th style="width:110px;">Foto</th>
                            <th>Nama & Kategori</th>
                            <th>Kode</th>
                            <th class="text-center">Stok Total</th>
                            <th class="text-center">Dipinjam</th>
                            <th class="text-center">Service</th>
                            <th class="text-center">Tersedia</th>
                            <th class="text-center">Status</th>
                            <th class="text-end" style="width:220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $item)
                            <tr>
                                {{-- FOTO --}}
                                <td>
                                    @if($item->foto_url)
                                        <img src="{{ $item->foto_url }}"
                                             alt="Foto {{ $item->nama_barang }}"
                                             class="rounded"
                                             style="width:80px;height:80px;object-fit:cover;">
                                    @else
                                        <div class="rounded d-flex align-items-center justify-content-center sipkam-empty-photo"
                                             style="width:80px;height:80px;">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- NAMA & KATEGORI --}}
                                <td class="fw-semibold">
                                    {{ $item->nama_barang }}
                                    <div class="small text-muted">
                                        {{ $item->kategori->nama_kategori ?? '-' }}
                                    </div>
                                </td>

                                {{-- KODE --}}
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $item->kode_barang }}
                                    </span>
                                </td>

                                {{-- STOK TOTAL --}}
                                <td class="text-center">
                                    {{ $item->stok ?? 0 }}
                                </td>

                                {{-- DIPINJAM --}}
                                <td class="text-center">
                                    {{ $item->stok_dipinjam }}
                                </td>

                                {{-- SERVICE --}}
                                <td class="text-center">
                                    {{ $item->stok_service }}
                                </td>

                                {{-- STOK TERSEDIA --}}
                                <td class="text-center fw-semibold">
                                    {{ $item->stok_tersedia }}
                                </td>

                                {{-- STATUS OTOMATIS --}}
                                <td class="text-center">
                                    @php $status = $item->status_otomatis; @endphp

                                    @if($status === 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($status === 'dipinjam')
                                        <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                    @elseif($status === 'dalam_service')
                                        <span class="badge bg-info text-dark">Sedang Service</span>
                                    @elseif($status === 'habis')
                                        <span class="badge bg-secondary">Stok Habis</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm mb-1 sipkam-btn-group" role="group">
                                        <a href="{{ route('barang.show', $item->id_barang) }}"
                                           class="btn btn-light border">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        <a href="{{ route('petugas.barang.edit', $item->id_barang) }}"
                                           class="btn btn-light border">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('petugas.barang.destroy', $item->id_barang) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-light border text-danger"
                                                    onclick="return confirm('Hapus barang ini?')">
                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Manajemen stok cepat (+1 / -1) --}}
                                    <div class="d-flex justify-content-end gap-1 sipkam-quick-stock">
                                        <form action="{{ route('petugas.barang.stok.kurang', $item->id_barang) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="jumlah" value="1">
                                            <button class="btn btn-outline-secondary btn-sm px-2 py-0"
                                                    type="submit"
                                                    title="Kurangi stok total 1">
                                                -
                                            </button>
                                        </form>
                                        <form action="{{ route('petugas.barang.stok.tambah', $item->id_barang) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="jumlah" value="1">
                                            <button class="btn btn-outline-secondary btn-sm px-2 py-0"
                                                    type="submit"
                                                    title="Tambah stok total 1">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-box-open me-2"></i>
                                    Belum ada barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </div>

    @else
        {{-- ====== VIEW MAHASISWA / USER BIASA: KARTU BARANG ====== --}}

        <style>
            :root {
                --sipkam-deep-1: #051F20;
                --sipkam-deep-2: #0B2B26;
                --sipkam-deep-3: #163832;
                --sipkam-deep-4: #235347;
                --sipkam-soft:  #8EB69B;
                --sipkam-neon:  #a7f3d0;
            }

            /* HEADER "Daftar Barang Tersedia" */
            body.sipkam-dark .sipkam-inventory-header .h4 {
                color: var(--sipkam-neon);
            }

            body.sipkam-dark .sipkam-inventory-header small,
            body.sipkam-dark .sipkam-inventory-header .text-muted {
                color: #9ca3af !important;
            }

            /* KARTU BARANG (MODE TERANG) */
            .sipkam-card-item {
                border-radius: 22px;
                overflow: hidden;
                background: #ffffff;
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
                transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
            }

            .sipkam-card-item:hover {
                transform: translateY(-4px);
                box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
            }

            .sipkam-card-item .card-body {
                background: #ffffff;
            }

            .sipkam-card-item .card-title {
                color: var(--sipkam-deep-2);
            }

            /* KARTU BARANG (MODE GELAP – HITAM + NEON HIJAU) */
            body.sipkam-dark .sipkam-card-item {
                background: radial-gradient(circle at top, var(--sipkam-deep-2) 0%, #020617 55%, #020617 100%);
                border: 1px solid rgba(15, 23, 42, 0.9);
                box-shadow: 0 24px 70px rgba(0, 0, 0, 0.9);
            }

            body.sipkam-dark .sipkam-card-item .card-body {
                background: transparent;
            }

            body.sipkam-dark .sipkam-card-item .card-title {
                color: var(--sipkam-neon);
            }

            body.sipkam-dark .sipkam-card-item .card-text,
            body.sipkam-dark .sipkam-card-item .text-muted,
            body.sipkam-dark .sipkam-card-item span {
                color: #9ca3af !important;
            }

            body.sipkam-dark .sipkam-card-item .fw-semibold,
            body.sipkam-dark .sipkam-card-item strong {
                color: var(--sipkam-neon) !important;
            }

            /* BADGE STATUS DI KARTU – GLOW DI MODE GELAP */
            .sipkam-card-item .badge {
                border-radius: 999px;
                padding-inline: 0.7rem;
                padding-block: 0.25rem;
                font-size: 0.7rem;
            }

            body.sipkam-dark .sipkam-card-item .badge.bg-success {
                background-color: #16a24aff !important;
                color: #022c22 !important;
                box-shadow: 0 0 16px rgba(34, 197, 94, 0.75);
            }

            body.sipkam-dark .sipkam-card-item .badge.bg-warning {
                background-color: #facc15 !important;
                color: #1f2937 !important;
            }

            body.sipkam-dark .sipkam-card-item .badge.bg-info {
                background-color: #38bdf8 !important;
                color: #e0f2fe !important;
            }

            body.sipkam-dark .sipkam-card-item .badge.bg-secondary {
                background-color: #4b5563 !important;
                color: #e5e7eb !important;
            }

            body.sipkam-dark .sipkam-card-item .badge.bg-danger {
                background-color: #ef4444 !important;
                color: #fee2e2 !important;
                box-shadow: 0 0 14px rgba(248, 113, 113, 0.6);
            }

            /* BUTTON "DETAIL BARANG" – NEON DI MODE GELAP */
            .sipkam-card-item .btn-primary {
                border-radius: 999px;
                font-weight: 600;
                font-size: 0.9rem;
                padding-block: 0.5rem;
                background: linear-gradient(135deg, var(--sipkam-deep-3), var(--sipkam-deep-4));
                border-color: transparent;
            }

            .sipkam-card-item .btn-primary:hover {
                filter: brightness(1.05);
            }

            body.sipkam-dark .sipkam-card-item .btn-primary {
                background: #22c55e;
                border-color: #22c55e;
                color: #020617;
                box-shadow: 0 0 22px rgba(34, 197, 94, 0.9);
            }

            body.sipkam-dark .sipkam-card-item .btn-primary:hover {
                background: #4ade80;
                border-color: #4ade80;
                box-shadow: 0 0 28px rgba(74, 222, 128, 1);
                color: #020617;
            }

            /* Placeholder gambar di mode gelap */
            body.sipkam-dark .sipkam-card-item .bg-light {
                background: #020617 !important;
                border-bottom: 1px solid rgba(15,23,42,0.9);
            }

            body.sipkam-dark .sipkam-card-item .fa-box-open {
                color: #4b5563 !important;
            }
        </style>

        <div class="sipkam-inventory-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted small mb-1">
                    Dashboard /
                    <span class="fw-semibold">Barang</span>
                </p>
                <h1 class="h4 mb-1 fw-bold">Daftar Barang Tersedia</h1>
                <small>
                    Lihat barang yang dapat Anda pinjam dari kampus
                </small>
            </div>
        </div>

        <div class="row g-3">
            @forelse($barang ?? $barangs ?? [] as $item)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm sipkam-card-item">
                        {{-- FOTO --}}
                        @if($item->foto_url)
                            <img src="{{ $item->foto_url }}"
                                 class="card-img-top"
                                 alt="Foto {{ $item->nama_barang }}"
                                 style="height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                 style="height:180px;">
                                <i class="fas fa-box-open text-muted fs-1"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold">{{ $item->nama_barang }}</h5>
                            <p class="card-text small text-muted mb-2">
                                {{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 100) }}
                            </p>

                            <p class="card-text small mb-3">
                                <span class="d-block text-muted">
                                    Kode: <span class="fw-semibold">{{ $item->kode_barang }}</span>
                                </span>
                                <span class="d-block text-muted">
                                    Stok tersedia:
                                    <span class="fw-semibold">{{ $item->stok_tersedia }}</span>
                                </span>
                                <span class="d-block">
                                    Status:
                                    @php $statusUser = $item->status_otomatis; @endphp

                                    @if($statusUser === 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($statusUser === 'dipinjam')
                                        <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                    @elseif($statusUser === 'dalam_service')
                                        <span class="badge bg-info text-dark">Sedang Service</span>
                                    @elseif($statusUser === 'habis')
                                        <span class="badge bg-secondary">Stok Habis</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($statusUser) }}</span>
                                    @endif
                                </span>
                            </p>

                            <div class="mt-auto">
                                <a href="{{ route('barang.show', $item->id_barang ?? $item->id ?? '') }}"
                                   class="btn btn-primary w-100">
                                    Detail Barang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="fas fa-info-circle me-2"></i>
                        Tidak ada barang tersedia saat ini.
                    </div>
                </div>
            @endforelse
        </div>
    @endif

</div>
@endsection

