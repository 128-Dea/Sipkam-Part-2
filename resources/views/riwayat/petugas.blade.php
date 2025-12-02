@extends('layouts.app')

@section('content')
@php
    $filters = $filters ?? ['kondisi' => null, 'search' => null];
@endphp

<style>
    :root {
        --hist-dark-1: #051F20;
        --hist-dark-2: #0B2B26;
        --hist-dark-3: #163832;
        --hist-mid:    #253547;
        --hist-soft:   #8EB69B;
        --hist-light:  #DAF1DE;
    }

    /* ===== WRAPPER DENGAN GRADIENT (FULL SECTION) ===== */
    .riw-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        background: transparent;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .riw-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media (max-width: 767.98px) {
        .riw-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .riw-inner {
            max-width: 100%;
        }
    }

    /* ===== HEADER ATAS (judul + tombol export) ===== */
    .riw-header {
        background: rgba(5, 31, 32, 0.96);
        border-radius: 20px 20px 0 0;
        padding: 18px 22px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: center;
        justify-content: space-between;
    }

    .riw-header-title h1.h3 {
        color: var(--hist-light);
        font-weight: 650;
        letter-spacing: .03em;
        margin-bottom: 4px;
        text-transform: capitalize;
    }

    .riw-header-title small {
        color: rgba(218,241,222,0.9) !important;
        font-size: 0.82rem;
    }

    .riw-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .riw-badge-total {
        background: rgba(218,241,222,0.08);
        color: var(--hist-light);
        border-radius: 999px;
        border: 1px solid rgba(142,182,155,0.7);
        font-weight: 500;
        font-size: 0.8rem;
        padding: 6px 14px;
        white-space: nowrap;
    }

    .btn-export-primary,
    .btn-export-secondary {
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 8px 16px;
        border-width: 1px;
        transition: all 0.22s ease;
    }

    .btn-export-primary {
        background: linear-gradient(135deg, var(--hist-soft), var(--hist-light));
        border-color: rgba(218,241,222,0.9);
        color: var(--hist-dark-1);
        box-shadow:
            0 10px 24px rgba(0,0,0,0.55),
            0 0 0 1px rgba(142,182,155,0.8);
    }

    .btn-export-primary:hover {
        filter: brightness(1.03);
        transform: translateY(-1px);
        color: var(--hist-dark-1);
    }

    .btn-export-secondary {
        background: transparent;
        border-color: rgba(226,232,240,0.9);
        color: #e2e8f0;
    }

    .btn-export-secondary:hover {
        background: rgba(15,23,42,0.5);
        color: #f9fafb;
    }

    @media (max-width: 575.98px) {
        .riw-header {
            border-radius: 16px 16px 0 0;
            padding: 16px;
        }
    }

    /* ===== CARD FILTER ===== */
    .riw-filter-card {
        border-radius: 0 0 18px 18px;
        margin-top: -1px;      /* nempel ke header */
        border: none;
        background: rgba(250,253,252,0.98);
        box-shadow: 0 16px 32px rgba(0,0,0,0.35);
    }

    .riw-filter-card .card-body {
        padding: 18px 22px 20px;
    }

    .riw-filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #475569;
        margin-bottom: 6px;
    }

    .riw-filter-card .form-control-modern,
    .riw-filter-card .form-select.form-control-modern {
        border-radius: 14px;
        border-color: #e2ece5;
        box-shadow: inset 0 0 0 1px rgba(148,163,184,0.15);
        font-size: 0.9rem;
        padding: 10px 14px;
        background-color: #0B2B26;
        color: #e5f9ee;
    }

    .riw-filter-card .form-control-modern::placeholder {
        color: rgba(226,232,240,0.8);
    }

    .riw-filter-card .form-control-modern:focus,
    .riw-filter-card .form-select.form-control-modern:focus {
        border-color: var(--hist-soft);
        box-shadow:
            0 0 0 1px rgba(142,182,155,0.45),
            0 0 0 4px rgba(142,182,155,0.18);
    }

    /* ===== CARD & TABEL ===== */
    .riw-table-card {
        margin-top: 20px;
        border-radius: 18px;
        border: none;
        background: rgba(250,253,252,0.97);
        box-shadow: 0 20px 40px rgba(0,0,0,0.45);
        overflow: hidden;
    }

    .riw-table-card .table {
        margin-bottom: 0;
    }

    .riw-table-card thead.table-light {
        background-color: var(--hist-dark-2) !important;
    }

    .riw-table-card thead.table-light th {
        background-color: transparent !important;
        color: #f9fafb;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        border-color: rgba(148,163,184,0.35);
    }

    .riw-table-card tbody td {
        font-size: 0.92rem;
        color: var(--hist-dark-3);
        border-color: #e1ebe4;
        vertical-align: middle;
    }

    .riw-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .riw-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .riw-table-card .text-muted {
        color: rgba(71,85,105,0.8) !important;
    }
</style>

<div class="riw-wrapper">
    <div class="riw-inner">

        {{-- HEADER + TOMBOL EXPORT + BADGE (logika sama, hanya dibungkus/di-style) --}}
        <div class="riw-header mb-0">
            <div class="riw-header-title">
                <h1 class="h3 mb-1">Riwayat Transaksi</h1>
                <small class="text-muted">Arsip peminjaman yang sudah selesai</small>
            </div>
            <div class="riw-header-right">
                <span class="riw-badge-total">
                    Total denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}
                </span>
                <a href="{{ route('petugas.riwayat.export.csv', $filters) }}"
                   class="btn btn-export-primary">
                    Download Excel (CSV)
                </a>
                <a href="{{ route('petugas.riwayat.export.html', $filters) }}"
                   class="btn btn-export-secondary">
                    Download PDF (HTML)
                </a>
            </div>
        </div>

        {{-- CARD FILTER (isi & logika TETAP, hanya tambah class untuk styling) --}}
        <div class="card border-0 shadow-sm mb-3 riw-filter-card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label riw-filter-label">Kondisi</label>
                        <select id="filter-kondisi" class="form-select form-control-modern">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ $filters['kondisi']==='tersedia' ? 'selected' : '' }}>Baik</option>
                            <option value="dalam_service" {{ $filters['kondisi']==='dalam_service' ? 'selected' : '' }}>Service / Rusak</option>
                            <option value="hilang" {{ $filters['kondisi']==='hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label riw-filter-label">Cari Mahasiswa / Barang</label>
                        <input type="text"
                               id="filter-search"
                               class="form-control form-control-modern"
                               value="{{ $filters['search'] }}"
                               placeholder="ketik nama atau barang">
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD TABEL (logika sama, hanya diberi class riw-table-card) --}}
        <div class="card border-0 shadow-sm riw-table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="riwayat-table">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Kondisi Barang</th>
                            <th>Total Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalian as $item)
                            @php
                                $p = $item->peminjaman;
                                $kondisi = $p?->barang?->status ?? 'tersedia';
                                $kondisiLabel = 'Baik';
                                $badge = 'success';
                                if ($kondisi === 'dalam_service') { $kondisiLabel = 'Service / Rusak'; $badge = 'warning'; }
                                if ($kondisi === 'hilang') { $kondisiLabel = 'Hilang'; $badge = 'danger'; }
                                $denda = $p?->denda?->sum('total_denda') ?? 0;
                                $isCanceled = $p?->status === 'dibatalkan';
                                $pinjamDisplay = $isCanceled ? '-' : \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y H:i');
                                $kembaliDisplay = $isCanceled ? '-' : \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y H:i');
                            @endphp
                            <tr data-row
                                data-nama="{{ $p?->pengguna?->nama ?? '-' }}"
                                data-email="{{ $p?->pengguna?->email ?? '-' }}"
                                data-barang="{{ $p?->barang?->nama_barang ?? '-' }}"
                                data-kode="{{ $p?->barang?->kode_barang ?? '-' }}"
                                data-pinjam="{{ $pinjamDisplay }}"
                                data-kembali="{{ $kembaliDisplay }}"
                                data-kondisi="{{ $kondisiLabel }}"
                                data-denda="Rp {{ number_format($denda, 0, ',', '.') }}"
                                data-catatan="{{ $item->catatan ?? '-' }}"
                            >
                                <td>{{ $p?->pengguna?->nama ?? '-' }}</td>
                                <td>{{ $p?->barang?->nama_barang ?? '-' }}</td>
                                <td class="text-nowrap">{{ $isCanceled ? '-' : \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y') }}</td>
                                <td class="text-nowrap">{{ $isCanceled ? '-' : \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y') }}</td>
                                <td><span class="badge bg-{{ $badge }}">{{ $kondisiLabel }}</span></td>
                                <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL DETAIL (tanpa perubahan logika) --}}
        <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light h-100">
                                    <div class="fw-semibold text-muted">Mahasiswa</div>
                                    <div id="d-nama" class="fw-semibold"></div>
                                    <small class="text-muted" id="d-email"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light h-100">
                                    <div class="fw-semibold text-muted">Barang</div>
                                    <div id="d-barang" class="fw-semibold"></div>
                                    <small class="text-muted" id="d-kode"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-white shadow-sm h-100">
                                    <div class="fw-semibold text-muted">Tanggal Pinjam</div>
                                    <div id="d-pinjam" class="fw-semibold"></div>
                                    <div class="fw-semibold text-muted mt-3">Tanggal Pengembalian</div>
                                    <div id="d-kembali" class="fw-semibold"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-white shadow-sm h-100">
                                    <div class="fw-semibold text-muted">Kondisi Barang</div>
                                    <div id="d-kondisi" class="fw-semibold"></div>
                                    <div class="fw-semibold text-muted mt-3">Total Denda</div>
                                    <div id="d-denda" class="fw-semibold text-danger"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded bg-white shadow-sm">
                                    <div class="fw-semibold text-muted">Catatan / riwayat</div>
                                    <p class="mb-0" id="d-catatan"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- /.riw-inner --}}
</div> {{-- /.riw-wrapper --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.dataset.detailContext = 'riwayat';

        const params = new URLSearchParams(window.location.search);
        const inputs = {
            kondisi: document.getElementById('filter-kondisi'),
            search: document.getElementById('filter-search'),
        };

        Object.values(inputs).forEach(el => {
            el?.addEventListener('change', applyFilters);
            el?.addEventListener('input', applyFilters);
        });

        function applyFilters() {
            params.set('kondisi', inputs.kondisi.value.trim());
            params.set('search', inputs.search.value.trim());

            // Hapus parameter kosong supaya query string bersih
            Array.from(params.keys()).forEach((key) => {
                if (!params.get(key)) {
                    params.delete(key);
                }
            });

            const query = params.toString();
            window.location = '{{ route('petugas.riwayat.index') }}' + (query ? '?' + query : '');
        }

        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.querySelectorAll('[data-row] .btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                document.getElementById('d-nama').textContent = row.dataset.nama;
                document.getElementById('d-email').textContent = row.dataset.email;
                document.getElementById('d-barang').textContent = row.dataset.barang;
                document.getElementById('d-kode').textContent = row.dataset.kode;
                document.getElementById('d-pinjam').textContent = row.dataset.pinjam;
                document.getElementById('d-kembali').textContent = row.dataset.kembali;
                document.getElementById('d-kondisi').textContent = row.dataset.kondisi;
                document.getElementById('d-denda').textContent = row.dataset.denda;
                document.getElementById('d-catatan').textContent = row.dataset.catatan;
                detailModal.show();
            });
        });
    });
</script>
@endsection
