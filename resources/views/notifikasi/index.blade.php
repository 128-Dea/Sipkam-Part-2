@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Notifikasi Sistem</h1>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($notifikasi as $item)
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">{{ $item->judul ?? 'Notifikasi' }}</h6>
                    <p class="mb-1">{{ $item->pesan ?? '-' }}</p>
                    <small class="text-muted">Barang: {{ $item->barang->nama_barang ?? '-' }} | Pengguna: {{ $item->pengguna->nama ?? '-' }}</small>
                </div>
                <form action="{{ route((auth()->user()?->role === 'petugas' ? 'petugas.notifikasi.destroy' : 'mahasiswa.notifikasi.destroy'), $item->id_notifikasi) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        @empty
            <div class="list-group-item text-center text-muted">Belum ada notifikasi.</div>
        @endforelse
    </div>
</div>
@endsection
