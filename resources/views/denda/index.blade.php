@extends('layouts.app')

@section('content')
<style>
    :root {
        --denda-dark-1: #051F20;
        --denda-dark-2: #0B2B26;
        --denda-dark-3: #163832;
        --denda-mid:     #253547;
        --denda-soft:    #8EB69B;
        --denda-light:   #DAF1DE;
    }

    /* ===== WRAPPER GRADIENT FULL SECTION ===== */
    .denda-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .denda-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media (max-width: 767.98px) {
        .denda-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .denda-inner {
            max-width: 100%;
        }
    }

    /* ===== HEADER ATAS ===== */
    .denda-header {
        background: rgba(5,31,32,0.96);
        border-radius: 20px 20px 0 0;
        padding: 18px 22px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0,0,0,0.6);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .denda-header .breadcrumb-text {
        font-size: 0.78rem;
        color: rgba(218,241,222,0.78);
    }

    .denda-header .breadcrumb-text span {
        color: var(--denda-light);
        font-weight: 600;
    }

    .denda-header h1.h4 {
        color: var(--denda-light);
        font-weight: 700;
        letter-spacing: .04em;
    }

    .denda-header small {
        color: rgba(218,241,222,0.88);
        font-size: 0.8rem;
    }

    @media (max-width: 575.98px) {
        .denda-header {
            border-radius: 16px 16px 0 0;
            padding: 16px;
        }
    }

    /* ===== ALERT SUCCESS ===== */
    .denda-alert {
        margin-top: 12px;
        border-radius: 999px;
        border: 1px solid rgba(34,197,94,0.35);
        box-shadow: 0 14px 30px rgba(15,118,110,0.35);
    }

    /* ===== CARD & TABEL DENDA ===== */
    .denda-table-card {
        margin-top: 16px;
        border-radius: 0 0 18px 18px;
        border: none;
        background: rgba(250,253,252,0.97);
        box-shadow: 0 20px 40px rgba(0,0,0,0.45);
        overflow: hidden;
    }

    .denda-table-card .table {
        margin-bottom: 0;
    }

    .denda-table-card thead.table-light {
        background-color: var(--denda-dark-2) !important;
    }

    .denda-table-card thead.table-light th {
        background-color: transparent !important;
        color: #f9fafb;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        border-color: rgba(148,163,184,0.4);
    }

    .denda-table-card tbody td {
        font-size: 0.9rem;
        color: var(--denda-dark-3);
        border-color: #e1ebe4;
        vertical-align: middle;
    }

    .denda-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .denda-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .denda-table-card .text-muted {
        color: rgba(71,85,105,0.8) !important;
    }
</style>

<div class="denda-wrapper">
    <div class="denda-inner">

        {{-- HEADER (isi aslinya tetap) --}}
        <div class="denda-header">
            <p class="breadcrumb-text mb-1">
                Dashboard / <span>Denda Peminjaman</span>
            </p>
            <h1 class="h4 mb-0 fw-bold">Denda Peminjaman</h1>
            <small>
                Daftar pengguna yang terkena denda (terlambat, rusak, hilang)
            </small>
        </div>

        {{-- ALERT SUCCESS (jika ada) --}}
        @if(session('success'))
            <div class="alert alert-success border-0 denda-alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- CARD TABEL DENDA (logika ASLI, hanya ditambah class) --}}
        <div class="card border-0 shadow-sm denda-table-card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Pengguna & Barang</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Bukti Transfer</th>
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($denda as $item)
                            @php
                                $jenis = $item->jenis;
                                $total = $item->total_denda ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $item->id_denda }}</td>

                                {{-- PENGGUNA & BARANG --}}
                                <td>
                                    <div class="fw-semibold">
                                        {{ $item->peminjaman->pengguna->nama ?? '-' }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $item->peminjaman->barang->nama_barang ?? '-' }}
                                    </div>
                                </td>

                                {{-- JENIS --}}
                                <td>
                                    @if($jenis === 'terlambat')
                                        <span class="badge bg-warning text-dark">Terlambat</span>
                                    @elseif($jenis === 'rusak')
                                        <span class="badge bg-danger">Rusak</span>
                                    @elseif($jenis === 'hilang')
                                        <span class="badge bg-dark">Hilang</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($jenis) }}</span>
                                    @endif
                                </td>

                                {{-- NOMINAL --}}
                                <td>
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>

                                {{-- DETAIL NOMINAL --}}
                                <td class="small">
                                    @if($jenis === 'terlambat')
                                        @php
                                            $menit = (int) round($total / 1000);
                                        @endphp
                                        Terlambat {{ $menit }} menit × Rp 1.000
                                    @elseif($jenis === 'hilang')
                                        @php
                                            $hargaBarang = optional($item->peminjaman->barang)->harga;
                                        @endphp
                                        Harga barang:
                                        @if($hargaBarang)
                                            Rp {{ number_format($hargaBarang, 0, ',', '.') }}
                                        @else
                                            (harga barang belum diisi)
                                        @endif
                                    @else
                                        {{ $item->keterangan ?? '-' }}
                                    @endif
                                </td>

                                {{-- METODE --}}
                                <td>
                                    @if($item->metode_pembayaran === 'cash')
                                        <span class="badge bg-success">Cash</span>
                                    @elseif($item->metode_pembayaran === 'transfer')
                                        <span class="badge bg-primary">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">Belum dipilih</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($item->status_pembayaran === 'sudah')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>

                                {{-- BUKTI TRANSFER --}}
                                <td class="small">
                                    @if($item->bukti_transfer_url)
                                        <a href="{{ $item->bukti_transfer_url }}"
                                           target="_blank"
                                           class="text-decoration-none">
                                            Lihat bukti
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{ route('petugas.denda.edit', $item->id_denda) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Detail & Pembayaran
                                        </a>

                                        @if($item->status_pembayaran === 'belum')
                                            {{-- Tombol cepat: verifikasi lunas (cash) --}}
                                            <form method="POST"
                                                  action="{{ route('petugas.denda.update', $item->id_denda) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_pembayaran" value="sudah">
                                                <input type="hidden" name="metode_pembayaran" value="cash">
                                                <button class="btn btn-sm btn-success w-100"
                                                        onclick="return confirm('Tandai denda ini sebagai lunas (cash)?')">
                                                    Verifikasi Lunas (Cash)
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Tidak ada denda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
