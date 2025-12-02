@extends('layouts.app')

@section('content')
<style>
    :root {
        --sipkam-dark-1: #051F20;
        --sipkam-dark-2: #0B2B26;
        --sipkam-dark-3: #163832;
        --sipkam-soft:   #8EB69B;
        --sipkam-light:  #DAF1DE;
    }

    .sipkam-perpanjangan-page {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);

        margin-top: -24px;
        margin-bottom: -24px;

        min-height: calc(100vh - 64px);

        padding: 40px 32px 56px;

        display: flex;
        align-items: flex-start;
    }

    @media (max-width: 992px) {
        .sipkam-perpanjangan-page {
            padding: 24px 16px 40px;
            margin-top: -16px;
            margin-bottom: -16px;
        }
    }

    /* LEBARIN DIKIT */
    .sipkam-perpanjangan-shell {
        flex: 1;
        width: 100%;
        max-width: 1200px;   /* sebelumnya 1100 */
        margin: 0 auto;
    }

    .sipkam-header-banner {
        margin-bottom: 1.5rem;
        border-radius: 24px;
        background: linear-gradient(135deg, #051F20, #0F3533);
        padding: 2rem 2.25rem;
        box-shadow: 0 22px 50px rgba(3, 26, 23, 0.35);
    }

    .sipkam-header-block h1.h3 {
        color: var(--sipkam-light);
        font-weight: 600;
        font-size: 2.3rem;
    }

    /* PAKSA subtitle SAMA PERSIS seperti judul */
    .sipkam-header-block .text-muted {
        color: var(--sipkam-light) !important;
    }

    @media (max-width: 576px) {
        .sipkam-header-block h1.h3 {
            font-size: 1.7rem;
        }

        .sipkam-header-block .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }
    }

    .sipkam-perpanjangan-page .btn-primary {
        background: linear-gradient(135deg, var(--sipkam-dark-2), var(--sipkam-dark-3));
        border-color: var(--sipkam-soft);
        border-radius: 999px;
        padding: 0.45rem 1.6rem;
        font-weight: 500;
        box-shadow:
            0 14px 28px rgba(5, 31, 32, 0.85),
            0 0 0 1px rgba(142, 182, 155, 0.55);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        color: #e9f7f0;
    }

    .sipkam-perpanjangan-page .btn-primary:hover {
        background: linear-gradient(135deg, var(--sipkam-dark-3), var(--sipkam-dark-2));
        transform: translateY(-1px);
        box-shadow:
            0 18px 36px rgba(5, 31, 32, 0.95),
            0 0 0 1px rgba(218, 241, 222, 0.75);
    }

    .sipkam-perpanjangan-page .btn-primary:active {
        transform: translateY(0);
        box-shadow:
            0 10px 20px rgba(5, 31, 32, 0.9),
            0 0 0 1px rgba(142, 182, 155, 0.6);
    }

    .sipkam-perpanjangan-page .card {
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        overflow: hidden;
    }

    .sipkam-perpanjangan-page .card .table thead.table-light {
        background-color: var(--sipkam-dark-2) !important;
    }

    .sipkam-perpanjangan-page .card .table thead.table-light th {
        background-color: transparent !important;
        color: #f8fafc;
        border-color: rgba(142, 182, 155, 0.3) !important;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .sipkam-perpanjangan-page .card .table tbody tr {
        background-color: #ffffff !important;
    }

    .sipkam-perpanjangan-page .card .table tbody tr:nth-child(even) {
        background-color: #f7faf8 !important;
    }

    .sipkam-perpanjangan-page .card .table tbody tr:hover {
        background-color: #ecf4ee !important;
    }

    .sipkam-perpanjangan-page .card .table th,
    .sipkam-perpanjangan-page .card .table td {
        border-color: #e0ebe3 !important;
        vertical-align: middle;
        font-size: 0.9rem;
        color: var(--sipkam-dark-3);
    }

    .sipkam-perpanjangan-page .card .text-muted {
        color: rgba(22, 56, 50, 0.75) !important;
    }

    .sipkam-perpanjangan-page .badge {
        border-radius: 999px;
        padding: 0.3rem 0.8rem;
        font-weight: 500;
        font-size: 0.75rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .sipkam-perpanjangan-page .badge.bg-success {
        background-color: var(--sipkam-soft) !important;
        color: var(--sipkam-dark-1);
    }

    .sipkam-perpanjangan-page .badge.bg-warning {
        background-color: #EBE69B !important;
        color: var(--sipkam-dark-3);
    }

    .sipkam-perpanjangan-page .badge.bg-danger {
        background-color: #F97373 !important;
        color: #220404;
    }
</style>

@php
    $perpanjanganRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp

<div class="sipkam-perpanjangan-page">
    <div class="sipkam-perpanjangan-shell">

        <div class="sipkam-header-banner">
            <div class="sipkam-header-block">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        <h1 class="h3 mb-1">Perpanjangan Peminjaman</h1>
                        <small class="text-muted">Pantau status pengajuan perpanjangan</small>
                    </div>
                    @if($perpanjanganRouteScope === 'mahasiswa')
                        <a href="{{ route($perpanjanganRouteScope . '.perpanjangan.create') }}" class="btn btn-primary">
                            Ajukan Perpanjangan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Peminjaman</th>
                            <th>Pengajuan</th>
                            <th>Perpanjangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perpanjangan as $item)
                            <tr>
                                <td>{{ $item->id_perpanjangan }}</td>
                                <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->waktu_pengajuan)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->waktu_perpanjangan)->format('d M Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status_persetujuan === 'disetujui' ? 'success' : ($item->status_persetujuan === 'ditolak' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($item->status_persetujuan) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
