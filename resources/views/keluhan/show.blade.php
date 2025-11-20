@extends('layouts.app')

@section('content')
@php
    $keluhanRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp
<div class="row">
    <div class="col-md-8">
        <h1 class="mb-4">Detail Keluhan #{{ $keluhan->id_keluhan }}</h1>

        @if($keluhan->foto_url)
            <div class="mb-4">
                <h5>Foto Bukti Keluhan</h5>
                <img src="{{ $keluhan->foto_url }}" alt="Foto keluhan" class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informasi Keluhan</h5>
                <table class="table">
                    <tr>
                        <th>ID Keluhan</th>
                        <td>{{ $keluhan->id_keluhan }}</td>
                    </tr>
                    <tr>
                        <th>Barang</th>
                        <td>{{ $keluhan->peminjaman->barang->nama_barang ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Pelapor</th>
                        <td>{{ $keluhan->pengguna->nama ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Detail Keluhan</th>
                        <td>{{ $keluhan->keluhan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ optional($keluhan->created_at)->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informasi Peminjaman</h5>
                <p><strong>Tanggal Pinjam:</strong> {{ optional($keluhan->peminjaman->tanggal_pinjam)->format('d M Y') ?? '-' }}</p>
                <p><strong>Tanggal Kembali:</strong> {{ optional($keluhan->peminjaman->tanggal_kembali)->format('d M Y') ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $keluhan->peminjaman->status ?? 'N/A' }}</p>
            </div>
        </div>

        @auth
            @if(auth()->user()->role === 'petugas')
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Aksi Petugas</h5>
                        <p>Keluhan ini dapat diproses untuk perbaikan atau service.</p>
                        <!-- Tambahkan tombol aksi petugas di sini jika diperlukan -->
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>

<div class="mt-4">
    <a href="{{ route($keluhanRouteScope . '.keluhan.index') }}" class="btn btn-secondary">Kembali ke Daftar Keluhan</a>
</div>
@endsection
