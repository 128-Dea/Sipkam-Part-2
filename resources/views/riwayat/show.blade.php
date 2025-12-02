@extends('layouts.app')

@section('content')
@php
    $peminjaman  = $riwayat->pengembalian->peminjaman ?? null;
    $barang      = $peminjaman->barang ?? null;
    $waktuAwal   = $peminjaman->waktu_awal ?? null;
    $waktuAkhir  = $peminjaman->waktu_akhir ?? null;
    $statusPinjam = $peminjaman->status ?? '-';
@endphp
<script>
    document.body.dataset.detailContext = 'riwayat';
</script>

<div
    class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-start align-items-md-center py-4 detail-overlay"
    style="overflow-y:auto;"
>
    <div class="container px-3 px-md-0 my-3 my-md-4">
        <div class="d-flex justify-content-center">
            <div class="card shadow-lg border-0 w-100" style="max-width: 900px; border-radius: 18px;">
                <div class="card-body p-4 p-md-5" style="max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="h4 mb-1">Detail riwayat</h2>
                            <small class="text-muted">riwayat #{{ $riwayat->id_riwayat }}</small>
                        </div>
                        <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-outline-secondary btn-sm">Tutup</a>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Informasi Barang</p>
                                <div class="fw-semibold mb-1">{{ $barang->nama_barang ?? '-' }}</div>
                                <div class="text-muted">{{ $barang->kode_barang ?? '-' }}</div>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $statusPinjam === 'selesai' ? 'success' : ($statusPinjam === 'berlangsung' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($statusPinjam) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Waktu</p>
                                <div class="text-muted small mb-1">Mulai</div>
                                <div class="fw-semibold mb-3">{{ optional($waktuAwal ? \Carbon\Carbon::parse($waktuAwal) : null)->format('d M Y H:i') ?? '-' }}</div>
                                <div class="text-muted small mb-1">Selesai</div>
                                <div class="fw-semibold">{{ optional($waktuAkhir ? \Carbon\Carbon::parse($waktuAkhir) : null)->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-3 bg-white p-3">
                        <p class="text-muted small fw-semibold mb-2">Ringkasan</p>
                        <div class="fw-semibold mb-1">
                            Pengembalian tercatat. Status peminjaman: {{ ucfirst($statusPinjam) }}.
                        </div>
                        <div class="text-muted">
                            Barang: {{ $barang->nama_barang ?? '-' }} ({{ $barang->kode_barang ?? '-' }}).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
