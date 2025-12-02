@extends('layouts.app')

@section('content')

<style>
    :root {
        /* palet hijau – sama seperti referensi */
        --sipkam-deep-1: #051F20;
        --sipkam-deep-2: #0B2B26;
        --sipkam-deep-3: #163832;
        --sipkam-deep-4: #235347;
        --sipkam-soft:  #8EB69B;
        --sipkam-mist:  #DAF1DE;
        --sipkam-neon:  #a7f3d0;
    }

    /* ===== WRAPPER HALAMAN (GRADIENT HIJAU) ===== */
    .sipkam-history-page {
        margin: -24px -32px -24px -32px;              /* tarik sampai pinggir konten */
        padding: 24px 32px 40px;
        min-height: calc(100vh - 64px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .sipkam-history-inner {
        width: 100%;
        max-width: 1150px;
    }

    .sipkam-history-banner {
        margin-bottom: 1.5rem;
        border-radius: 24px;
        padding: 1.8rem 2.2rem;
        background: linear-gradient(135deg, #051F20, #0F3533);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        color: #f4f9f2;
    }

    .sipkam-history-banner h1 {
        margin: 0;
        font-size: 2.3rem;
        font-weight: 600;
    }

    .sipkam-history-banner small {
        display: block;
        color: rgba(244, 249, 242, 0.85);
    }

    @media (max-width: 767.98px) {
        .sipkam-history-page {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }

        .sipkam-history-inner {
            max-width: 100%;
        }
    }

    /* ===== HEADER JUDUL ===== */
    .sipkam-history-title {
        color: var(--sipkam-mist);
        font-weight: 650;
        letter-spacing: 0.03em;
        margin-bottom: 1.25rem;
        text-shadow: 0 8px 18px rgba(0, 0, 0, 0.7);
    }

    /* ===== CARD & TABEL ===== */
    .sipkam-history-card {
        border-radius: 20px;
        border: 1px solid rgba(222, 247, 231, 0.9);
        background: rgba(255, 255, 255, 0.97);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
        overflow: hidden;
    }

    .sipkam-history-card .table {
        margin-bottom: 0;
    }

    .sipkam-history-card thead th {
        background: linear-gradient(180deg, var(--sipkam-deep-2), var(--sipkam-deep-3));
        color: #f9fafb;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        border-color: rgba(148, 163, 184, 0.35);
    }

    .sipkam-history-card tbody td {
        font-size: 0.92rem;
        color: var(--sipkam-deep-3);
        border-color: #e1ebe4;
        vertical-align: middle;
    }

    .sipkam-history-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .sipkam-history-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .sipkam-history-card .text-muted {
        color: rgba(71, 85, 105, 0.8) !important;
    }

    /* BADGE DENDA / TIDAK ADA */
    .sipkam-history-card .badge {
        border-radius: 999px;
        padding: 0.25rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* ===== FORM (kalau nanti ditambah filter) – MODE TERANG ===== */
    .sipkam-history-page .form-control,
    .sipkam-history-page .form-select {
        border-radius: 999px;
        border: 1px solid rgba(35, 83, 71, 0.22);
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 6px 16px rgba(3, 26, 23, 0.08);
        font-size: 0.9rem;
    }

    .sipkam-history-page .form-control:focus,
    .sipkam-history-page .form-select:focus {
        border-color: var(--sipkam-soft);
        box-shadow:
            0 0 0 1px rgba(142, 182, 155, 0.6),
            0 0 0 4px rgba(142, 182, 155, 0.15);
    }

    body.sipkam-dark .sipkam-history-title {
        color: var(--sipkam-neon);
        text-shadow: 0 0 18px rgba(34, 197, 94, 0.85);
    }

    body.sipkam-dark .sipkam-history-card {
        background: #020617;
        border-color: #111827;
        box-shadow: 0 26px 70px rgba(0, 0, 0, 0.95);
        color: #e5e7eb;
    }

    body.sipkam-dark .sipkam-history-card thead th {
        background: #020617;
        color: var(--sipkam-neon);
        border-color: #1f2937;
    }

    body.sipkam-dark .sipkam-history-card tbody td {
        border-color: #111827;
        color: #e5e7eb;
    }

    body.sipkam-dark .sipkam-history-card tbody tr:nth-child(even) {
        background-color: #020617;
    }

    body.sipkam-dark .sipkam-history-card tbody tr:hover {
        background: #020617;
        box-shadow: 0 0 0 rgba(0,0,0,0);
    }

    body.sipkam-dark .sipkam-history-card .text-muted {
        color: #9ca3af !important;
    }

    /* badge glow di dark mode */
    body.sipkam-dark .sipkam-history-card .badge.bg-success {
        background-color: #16a34a !important;
        color: #022c22 !important;
        box-shadow: 0 0 16px rgba(34, 197, 94, 0.75);
    }

    body.sipkam-dark .sipkam-history-card .badge.bg-danger {
        background-color: #ef4444 !important;
        color: #fee2e2 !important;
        box-shadow: 0 0 14px rgba(248, 113, 113, 0.6);
    }

    /* FORM DI DARK MODE: background gelap, teks hijau neon */
    body.sipkam-dark .sipkam-history-page .form-control,
    body.sipkam-dark .sipkam-history-page .form-select {
        background: #020617;
        border-color: #1f2937;
        color: var(--sipkam-neon);
        box-shadow:
            0 0 0 1px rgba(15, 23, 42, 0.9),
            0 0 18px rgba(34, 197, 94, 0.25);
    }

    body.sipkam-dark .sipkam-history-page .form-control::placeholder {
        color: rgba(148, 163, 184, 0.85);
    }
</style>

<div class="sipkam-history-page">
    <div class="sipkam-history-inner">

        {{-- BANNER JUDUL --}}
        <div class="sipkam-history-banner">
            <h1>Riwayat Peminjaman</h1>
            <small>Mengarsip peminjaman yang sudah selesai agar bisa ditelusuri kembali.</small>
        </div>

        {{-- CARD TABEL (logika & isi sama persis, hanya tambah class) --}}
        <div class="card border-0 shadow-sm sipkam-history-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Total Denda</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                            @php
                                $peminjaman = $item->pengembalian->peminjaman ?? null;
                                $denda = $peminjaman?->denda?->sum('total_denda') ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $peminjaman->pengguna->nama ?? '-' }}</td>
                                <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ optional($peminjaman?->waktu_awal ? \Carbon\Carbon::parse($peminjaman->waktu_awal) : null)->format('d M Y') }}</td>
                                <td>{{ optional($item->pengembalian?->waktu_pengembalian)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @if($denda > 0)
                                        <span class="badge bg-danger">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="badge bg-success">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('mahasiswa.riwayat.show', $item->id_riwayat) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Riwayat masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
