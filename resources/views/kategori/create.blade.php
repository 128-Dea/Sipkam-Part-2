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

    /* BACKGROUND HALAMAN + POSISI CARD */
    .kat-create-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        background: linear-gradient(
            180deg,
            var(--kat-dark-1) 0%,
            var(--kat-dark-3) 30%,
            var(--kat-soft)   70%,
            var(--kat-light)  100%
        );
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .kat-create-inner {
        width: 100%;
        max-width: 720px;
    }

    @media (max-width: 767.98px) {
        .kat-create-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .kat-create-inner {
            max-width: 100%;
        }
    }

    /* HEADER JUDUL */
    .kat-create-header {
        background: rgba(5, 31, 32, 0.96);
        border-radius: 20px 20px 0 0;
        padding: 18px 22px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .kat-create-header h1.h3 {
        color: var(--kat-light);
        font-weight: 650;
        letter-spacing: .03em;
        margin: 0;
    }

    .kat-create-header small.text-muted {
        color: rgba(218,241,222,0.9) !important;
    }

    /* CARD FORM */
    .kat-create-card {
        border-radius: 0 0 20px 20px;
        margin-top: -1px; /* nempel ke header */
        border: none;
        background: rgba(250,253,252,0.98);
        box-shadow: 0 18px 38px rgba(0,0,0,0.45);
        overflow: hidden;
    }

    .kat-create-card .card-body {
        padding: 20px 22px 10px;
    }

    .kat-create-card .card-footer {
        padding: 12px 22px 16px;
        border-top: 1px solid rgba(209,213,219,0.8);
        background: linear-gradient(90deg, rgba(218,241,222,0.95), rgba(142,182,155,0.9));
    }

    /* INPUT & LABEL */
    .kat-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #475569;
        margin-bottom: 6px;
    }

    .kat-input {
        border-radius: 12px;
        border: 1px solid #d1e3d9;
        padding: 0.7rem 0.9rem;
        font-size: 0.94rem;
        box-shadow: inset 0 0 0 1px rgba(148,163,184,0.15);
    }

    .kat-input:focus {
        border-color: var(--kat-soft);
        box-shadow:
            0 0 0 1px rgba(142,182,155,0.5),
            0 0 0 4px rgba(142,182,155,0.2);
    }

    /* BUTTONS */
    .kat-btn-secondary {
        border-radius: 999px;
        padding: 0.45rem 1.1rem;
        font-size: 0.9rem;
        border: 1px solid rgba(55,65,81,0.25);
        background: transparent;
        color: #111827;
        font-weight: 500;
    }

    .kat-btn-secondary:hover {
        background: rgba(15,23,42,0.05);
        color: #020617;
    }

    .kat-btn-primary {
        border-radius: 999px;
        padding: 0.45rem 1.3rem;
        font-size: 0.9rem;
        border: none;
        font-weight: 600;
        background: linear-gradient(135deg, var(--kat-soft), var(--kat-light));
        color: var(--kat-dark-1);
        box-shadow:
            0 10px 24px rgba(0,0,0,0.4),
            0 0 0 1px rgba(142,182,155,0.8);
    }

    .kat-btn-primary:hover {
        filter: brightness(1.04);
        transform: translateY(-1px);
        color: var(--kat-dark-1);
    }
</style>

<div class="kat-create-wrapper">
    <div class="kat-create-inner">

        {{-- HEADER (judul asli, hanya dibungkus) --}}
        <div class="kat-create-header mb-0">
            <h1 class="h3 mb-0">Tambah Kategori</h1>
            <small class="text-muted">Tambahkan kategori baru untuk mengelompokkan barang peminjaman.</small>
        </div>

        {{-- FORM ASLI, LOGIKA TIDAK DIUBAH --}}
        <form method="POST"
              action="{{ route('petugas.kategori.store') }}"
              class="card border-0 shadow-sm kat-create-card">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label kat-label">Nama Kategori</label>
                    <input type="text"
                           name="nama_kategori"
                           value="{{ old('nama_kategori') }}"
                           class="form-control kat-input"
                           required>
                    @error('nama_kategori')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('petugas.kategori.index') }}" class="btn kat-btn-secondary me-2">Batal</a>
                <button class="btn kat-btn-primary" type="submit">Simpan</button>
            </div>
        </form>

    </div>
</div>
@endsection
