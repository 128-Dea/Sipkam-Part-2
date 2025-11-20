@extends('layouts.app')

@section('content')
@php
    $keluhanRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Keluhan Mahasiswa</h1>
        <small class="text-muted">Kelola laporan gangguan selama peminjaman</small>
    </div>
    @if($keluhanRouteScope === 'mahasiswa')
        <a href="{{ route($keluhanRouteScope . '.keluhan.create') }}" class="btn btn-primary">Laporkan Keluhan</a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Barang</th>
                    <th>Keluhan</th>
                    <th>Pelapor</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($keluhan as $item)
                    <tr>
                        <td>{{ $item->id_keluhan }}</td>
                        <td style="width: 100px;">
                            @if($item->foto_url)
                                <img src="{{ $item->foto_url }}" alt="Foto keluhan" class="img-thumbnail" style="max-height: 60px; object-fit: cover;">
                            @else
                                <span class="text-muted small">Tidak ada foto</span>
                            @endif
                        </td>
                        <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td>{{ Str::limit($item->keluhan, 50) }}</td>
                        <td>{{ $item->pengguna->nama ?? '-' }}</td>
                        <td>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</td>
                        <td>
                            <a href="{{ route($keluhanRouteScope . '.keluhan.show', $item->id_keluhan) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada keluhan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
