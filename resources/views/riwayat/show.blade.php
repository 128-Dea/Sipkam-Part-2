@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1">Detail Riwayat</h1>
        <small class="text-muted">Peminjaman #{{ $riwayat->id_peminjaman }}</small>
    </div>
    <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Informasi Barang</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ $riwayat->barang->nama_barang ?? '-' }}</p>
                <p class="mb-1"><strong>Kode:</strong> {{ $riwayat->barang->kode_barang ?? '-' }}</p>
                <p class="mb-0"><strong>Status:</strong> {{ ucfirst($riwayat->status) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Waktu</h5>
                <p class="mb-1">Mulai: {{ \Carbon\Carbon::parse($riwayat->waktu_awal)->format('d M Y H:i') }}</p>
                <p class="mb-0">Selesai: {{ \Carbon\Carbon::parse($riwayat->waktu_akhir)->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
