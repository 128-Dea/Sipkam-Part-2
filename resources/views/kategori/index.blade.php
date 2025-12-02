@extends('layouts.app')

@section('content')

<style>
    :root {
        --kat-dark-1: #051F20;
        --kat-dark-2: #0B2B26;
        --kat-dark-3: #163832;
        --kat-mid:    #253547;
        --kat-soft:   #8EB69B;
        --kat-light:  #DAF1DE;
    }

    /* WRAPPER HALAMAN */
    .kategori-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .kategori-inner {
        width: 100%;
        max-width: 1050px;
    }

    @media (max-width: 767.98px) {
        .kategori-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }

        .kategori-inner {
            max-width: 100%;
        }
    }

    /* HEADER + TOMBOL */
    .kategori-header {
        background: rgba(5, 31, 32, 0.96);
        border-radius: 20px 20px 0 0;
        padding: 18px 22px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kategori-header h1.h3 {
        color: var(--kat-light);
        font-weight: 650;
        letter-spacing: .03em;
        margin-bottom: 4px;
    }

    .kategori-header small.text-muted {
        color: rgba(218, 241, 222, 0.9) !important;
    }

    .btn-kategori-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--kat-soft), var(--kat-light));
        border: 1px solid rgba(218,241,222,0.9);
        color: var(--kat-dark-1);
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        font-size: 0.9rem;
        box-shadow:
            0 12px 26px rgba(0,0,0,.45),
            0 0 0 1px rgba(142,182,155,.7);
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        text-decoration: none;
    }

    .btn-kategori-primary:hover {
        filter: brightness(1.03);
        color: var(--kat-dark-1);
        text-decoration: none;
        transform: translateY(-1px);
    }

    @media (max-width: 767.98px) {
        .kategori-header {
            border-radius: 16px 16px 0 0;
            padding: 16px 16px;
        }

        .btn-kategori-primary {
            width: 100%;
            justify-content: center;
        }
    }

    /* CARD TABEL */
    .kategori-table-card {
        border-radius: 0 0 18px 18px;
        border: none;
        margin-top: -1px;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.45);
        overflow: hidden;
    }

    .kategori-table-card .table {
        margin-bottom: 0;
    }

    .kategori-table-card thead {
        background: linear-gradient(135deg, var(--kat-dark-2), var(--kat-dark-3));
    }

    .kategori-table-card thead th {
        background: transparent;
        color: #e5f9ee;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-size: 0.78rem;
        border: none;
        padding: 0.7rem 0.9rem;
    }

    .kategori-table-card tbody td {
        padding: 0.75rem 0.9rem;
        border-color: #e1ebe4;
        font-size: 0.9rem;
        color: var(--kat-dark-3);
        vertical-align: middle;
    }

    .kategori-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .kategori-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .kategori-table-card .text-muted {
        color: rgba(71,85,105,0.8) !important;
    }

    .kategori-table-card .btn.btn-sm {
        border-radius: 999px;
        padding-inline: .85rem;
        font-size: 0.8rem;
    }
</style>

<div class="kategori-wrapper">
    <div class="kategori-inner">

        {{-- HEADER ASLI, HANYA DIPERINDAH --}}
        <div class="kategori-header mb-0">
            <div>
                <h1 class="h3 mb-1">Kategori Barang</h1>
                <small class="text-muted">Kelola daftar kategori untuk semua barang peminjaman.</small>
            </div>
            <a href="{{ route('petugas.kategori.create') }}" class="btn btn-kategori-primary">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>

        {{-- CARD TABEL (logika & loop TETAP SAMA) --}}
        <div class="card kategori-table-card">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $item)
                            <tr>
                                <td>{{ $item->id_kategori }}</td>
                                <td>{{ $item->nama_kategori }}</td>
                                <td class="text-end">
                                    <a href="{{ route('petugas.kategori.edit', $item->id_kategori) }}" class="btn btn-sm btn-outline-primary me-1">
                                        Edit
                                    </a>
                                    <form action="{{ route('petugas.kategori.destroy', $item->id_kategori) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kategori ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada kategori.
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
