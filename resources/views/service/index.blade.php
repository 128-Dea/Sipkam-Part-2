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

    .sipkam-service-page {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        min-height: calc(100vh - 64px);
        padding: 40px 32px 56px;
        margin-top: -24px;
        margin-bottom: -24px;
        display: flex;
        align-items: flex-start;
    }

    .sipkam-service-shell {
        flex: 1;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .sipkam-service-banner {
        margin-bottom: 1.5rem;
        border-radius: 24px;
        background: linear-gradient(135deg, #051F20, #0F3533);
        padding: 1.9rem 2.25rem;
        box-shadow: 0 22px 50px rgba(3, 26, 23, 0.35);
    }

    .sipkam-service-banner h1 {
        color: var(--sipkam-light);
        font-weight: 600;
        font-size: 2.3rem;
        margin-bottom: 0.4rem;
    }

    .sipkam-service-banner .text-muted {
        color: rgba(233, 247, 240, 0.85) !important;
    }

    .sipkam-service-page .card {
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .sipkam-service-page .table {
        margin-bottom: 0;
    }

    .sipkam-service-page .table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: none;
        background: linear-gradient(135deg, var(--sipkam-dark-2), var(--sipkam-dark-3)) !important;
        color: #e5f9ee;
    }

    .sipkam-service-page .table tbody td {
        border-color: #e2ece5;
    }

    .sipkam-service-page .badge {
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .sipkam-service-page .form-control {
        border-radius: 12px;
        border-color: rgba(15, 23, 42, 0.15);
        box-shadow: inset 0 0 0 1px rgba(132, 162, 154, 0.35);
    }
</style>

@php
    $title = 'Daftar Service Barang';
    $description = 'List barang yang sedang / pernah dalam perbaikan, lengkap dengan rincian kerusakan.';
@endphp

<div class="sipkam-service-page">
    <div class="sipkam-service-shell">
        <div class="sipkam-service-banner">
            <div class="d-flex justify-content-between flex-wrap align-items-center gap-3">
                <div>
                    <p class="text-muted small mb-1">Dashboard / <span class="fw-semibold text-light">Service</span></p>
                    <h1 class="mb-0">{{ $title }}</h1>
                    <small class="text-muted">{{ $description }}</small>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Barang & Peminjam</th>
                            <th>Rincian Kerusakan</th>
                            <th style="width:160px;">Tgl Masuk Service</th>
                            <th style="width:170px;">Estimasi Selesai</th>
                            <th style="width:130px;">Status</th>
                            <th style="width:260px;">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($service as $item)
                            @php
                                $barang   = optional(optional(optional($item->keluhan)->peminjaman)->barang);
                                $pengguna = optional(optional(optional($item->keluhan)->peminjaman)->pengguna);
                            @endphp

                            <tr>
                                <td>#{{ $item->id_service }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $barang->nama_barang ?? '-' }}</div>
                                    <div class="small text-muted">Peminjam: {{ $pengguna->nama ?? '-' }}</div>
                                </td>
                                <td class="small">
                                    {{ \Illuminate\Support\Str::limit($item->keluhan->keluhan ?? '-', 120) }}
                                </td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('petugas.service.update', $item->id_service) }}"
                                          class="d-flex flex-column gap-1">
                                        @csrf
                                        @method('PUT')

                                        <input type="datetime-local"
                                               name="tgl_masuk_service"
                                               class="form-control form-control-sm"
                                               step="60"
                                               value="{{ $item->tgl_masuk_service ? $item->tgl_masuk_service->format('Y-m-d\\TH:i') : '' }}">
                                </td>
                                <td>
                                        <input type="datetime-local"
                                               name="estimasi_selesai"
                                               class="form-control form-control-sm"
                                               step="60"
                                               value="{{ $item->estimasi_selesai ? $item->estimasi_selesai->format('Y-m-d\\TH:i') : '' }}">
                                </td>
                                <td>
                                    <div class="mb-1">
                                        @if($item->status === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($item->status === 'diperbaiki')
                                            <span class="badge bg-warning text-dark">Proses</span>
                                        @else
                                            <span class="badge bg-secondary">Mengantri</span>
                                        @endif
                                    </div>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="mengantri"  @selected($item->status === 'mengantri')>Mengantri</option>
                                        <option value="diperbaiki" @selected($item->status === 'diperbaiki')>Proses</option>
                                        <option value="selesai" @selected($item->status === 'selesai')>Selesai</option>
                                    </select>
                                </td>
                                <td>
                                        <button type="submit"
                                                class="btn btn-sm btn-primary w-100 mb-1">
                                            Simpan Perubahan
                                        </button>

                                        @if($item->status !== 'selesai')
                                            <button type="submit"
                                                    name="status"
                                                    value="selesai"
                                                    class="btn btn-sm btn-outline-success w-100"
                                                    onclick="return confirm('Tandai service ini selesai dan kembalikan barang ke stok?')">
                                                Tandai Selesai & Kembalikan Stok
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data service.
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
