@extends('layouts.app')

@section('content')
@php
    $serahTerimaScope = auth()->user()?->role === 'mahasiswa' ? 'mahasiswa' : 'petugas';
@endphp
<div class="d-flex justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1">Serah Terima Barang</h1>
        <small class="text-muted">Riwayat perpindahan tanggung jawab barang</small>
    </div>
    @if($serahTerimaScope === 'mahasiswa')
        <a href="{{ route($serahTerimaScope . '.serahterima.create') }}" class="btn btn-primary">Buat Serah Terima</a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Lama ? Baru</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($serahTerima as $item)
                    <tr>
                        <td>{{ $item->id_serah_terima }}</td>
                        <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->penggunaLama->nama ?? '?' }} ? {{ $item->penggunaBaru->nama ?? '?' }}</td>
                        <td>{{ optional($item->waktu)->format('d M Y H:i') }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($item->status_persetujuan) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada catatan serah terima.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
