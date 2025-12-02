@extends('layouts.app')

@section('content')
<style>
    :root {
        --notif-dark-1: #051F20;
        --notif-dark-2: #0B2B26;
        --notif-soft: #8EB69B;
        --notif-light: #DAF1DE;
    }

    .sipkam-notif-page {
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

    .sipkam-notif-shell {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .sipkam-notif-banner {
        margin-bottom: 1.5rem;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--notif-dark-1), var(--notif-dark-2));
        padding: 1.8rem 2.25rem;
        box-shadow: 0 22px 50px rgba(3, 26, 23, 0.35);
    }

    .sipkam-notif-banner h1 {
        color: #f4f9f2;
        font-size: 2.3rem;
        font-weight: 600;
        margin-bottom: 0.35rem;
    }

    .sipkam-notif-banner .text-muted {
        color: rgba(244, 249, 242, 0.85) !important;
        font-size: 0.95rem;
    }

    .sipkam-notif-page .card {
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }

    .sipkam-notif-page .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(226, 232, 240, 0.45);
        padding: 1rem 1.25rem;
    }

    .sipkam-notif-page .list-group-item:last-child {
        border-bottom: none;
    }

    .sipkam-notif-page .btn {
        border-radius: 999px;
        box-shadow: 0 10px 20px rgba(3, 26, 23, 0.1);
    }

    .sipkam-notif-page .text-muted small {
        display: block;
        margin-top: 0.35rem;
    }
</style>

<div class="sipkam-notif-page">
    <div class="sipkam-notif-shell">
        <div class="sipkam-notif-banner">
            <div class="d-flex flex-column gap-1">
                <p class="text-muted small mb-0">Dashboard / Notifikasi</p>
                <h1 class="mb-0">Notifikasi Sistem</h1>
                <small>Cek notifikasi terbaru agar kamu tidak melewatkan update penting.</small>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @forelse($notifikasi as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $item->judul ?? 'Notifikasi' }}</h6>
                            <p class="mb-1">{{ $item->pesan ?? '-' }}</p>
                            @if(auth()->user()?->role !== 'petugas')
                                <small class="text-muted">Barang: {{ $item->barang->nama_barang ?? '-' }} | Pengguna: {{ $item->pengguna->nama ?? '-' }}</small>
                            @endif
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
    </div>
</div>

@endsection
