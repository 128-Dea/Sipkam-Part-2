@extends('layouts.app')

@section('content')
<style>
    :root {
        --inv-deep-1: #051F20;
        --inv-deep-2: #0B2B26;
        --inv-deep-3: #163832;
        --inv-soft:   #8EB69B;
        --inv-light:  #DAF1DE;
    }

    /* ====== BACKGROUND HALAMAN (LUAR FORM) GRADIENT VERTIKAL ====== */
    body {
        background: linear-gradient(
            180deg,
            var(--inv-deep-1) 0%,
            var(--inv-deep-3) 35%,
            var(--inv-soft)   75%,
            var(--inv-light)  100%
        ) !important;
    }

    main.py-4 {
        background: transparent !important;
        padding-top: 1.75rem;
        padding-bottom: 2.5rem;
        min-height: 100vh;
    }

    .barang-create-wrapper {
        max-width: 100%;
    }

    /* ====== CARD / FORM WRAPPER – PUTIH BERSIH ====== */
    .barang-create-card {
        background: #ffffff;              /* <- putih */
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, 0.45);
        box-shadow:
            0 26px 60px rgba(0, 0, 0, 0.65),
            0 0 0 1px rgba(15, 23, 42, 0.25);
        overflow: hidden;
    }

    /* HEADER FORM: GRADIENT HIJAU TEAL */
    .barang-create-header {
        background: linear-gradient(
            120deg,
            var(--inv-deep-1) 0%,
            var(--inv-deep-2) 40%,
            var(--inv-soft)   100%
        );
        border-bottom: 1px solid rgba(148, 163, 184, 0.55);
    }

    .barang-create-header h5 {
        letter-spacing: .04em;
        text-transform: uppercase;
        font-size: .82rem;
    }

    .barang-create-header small {
        font-size: .75rem;
    }

    .barang-create-header .avatar-dot {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: radial-gradient(circle at 30% 30%,
            #ffffff,
            #e2fbe8 45%,
            #0f172a 100%);
        box-shadow: 0 0 22px rgba(167, 243, 208, 0.95);
    }

    /* BODY FORM – PUTIH TIPIS DENGAN SEDIKIT GLOW ATAS */
    .barang-create-body {
        background: linear-gradient(
            180deg,
            rgba(255, 255, 255, 0.98) 0%,
            #ffffff 40%,
            #ffffff 100%
        );
    }

    .barang-create-body .form-label {
        color: #0f172a;
        font-weight: 600;
        letter-spacing: .03em;
        text-transform: uppercase;
        font-size: .72rem;
    }

    .barang-create-body .form-control,
    .barang-create-body .form-select {
        border-radius: 10px;
        border-color: rgba(148, 163, 184, 0.6);
        background-color: #ffffff;
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.15);
        font-size: .86rem;
    }

    .barang-create-body .input-group-text {
        border-radius: 10px 0 0 10px;
        border-color: rgba(148, 163, 184, 0.6);
        background: linear-gradient(135deg, var(--inv-soft), var(--inv-light));
        color: var(--inv-deep-1);
        font-weight: 600;
    }

    .barang-create-body .form-control:focus,
    .barang-create-body .form-select:focus {
        border-color: var(--inv-soft);
        box-shadow:
            0 0 0 1px rgba(142, 182, 155, 0.9),
            0 0 0 4px rgba(142, 182, 155, 0.28);
    }

    .barang-create-body small.text-muted {
        color: rgba(75, 85, 99, 0.85) !important;
        font-size: .78rem;
    }

    /* FOOTER CARD – PUTIH + TOMBOL GLOW HIJAU */
    .barang-create-footer {
        background: #ffffff;
        border-top: 1px solid rgba(226, 232, 240, 0.9);
    }

    .barang-create-footer .btn-light {
        border-radius: 999px;
        border-color: rgba(148, 163, 184, 0.7);
        background: #f9fafb;
        color: #0f172a;
        font-size: .86rem;
    }

    .barang-create-footer .btn-primary {
        border-radius: 999px;
        border: none;
        background: linear-gradient(135deg, var(--inv-soft), var(--inv-light));
        color: var(--inv-deep-1);
        font-weight: 600;
        font-size: .9rem;
        box-shadow:
            0 16px 30px rgba(15, 23, 42, 0.6),
            0 0 18px rgba(167, 243, 208, 0.95);
    }

    .barang-create-footer .btn-primary:hover {
        filter: brightness(1.04);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid barang-create-wrapper">

    {{-- FORM TAMBAH BARANG (LOGIKA ASLI TIDAK DIUBAH) --}}
    <form method="POST"
          action="{{ route('petugas.barang.store') }}"
          class="card border-0 shadow-sm rounded-4 overflow-hidden w-100 barang-create-card"
          enctype="multipart/form-data">
        @csrf

        {{-- HEADER --}}
        <div class="card-header border-0 py-3 px-4 d-flex justify-content-between align-items-center barang-create-header">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-dot"></div>
                <div class="text-white">
                    <h5 class="mb-0 fw-semibold">Tambah Barang</h5>
                    <small class="opacity-80">
                        Lengkapi informasi barang inventaris kampus
                    </small>
                </div>
            </div>

            <a href="{{ route('barang.index') }}"
               class="btn btn-outline-light btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-left-short me-1"></i> Kembali
            </a>
        </div>

        {{-- BODY FORM --}}
        <div class="card-body p-4 barang-create-body">
            <div class="row g-4">

                {{-- NAMA BARANG --}}
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text"
                           name="nama_barang"
                           value="{{ old('nama_barang') }}"
                           class="form-control form-control-sm @error('nama_barang') is-invalid @enderror"
                           required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KATEGORI --}}
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori"
                            class="form-select form-select-sm @error('id_kategori') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id_kategori }}"
                                @selected(old('id_kategori')==$item->id_kategori)>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KODE BARANG --}}
                <div class="col-12">
                    <label class="form-label">Kode Barang</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           value="Akan dibuat otomatis saat disimpan"
                           disabled
                           readonly>
                    <small class="text-muted fst-italic">
                        Kode barang digenerate otomatis dengan format BRG-XXXX.
                    </small>
                </div>

                {{-- HARGA --}}
                <div class="col-md-6">
                    <label class="form-label">Harga</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number"
                               name="harga"
                               value="{{ old('harga') }}"
                               class="form-control @error('harga') is-invalid @enderror"
                               min="0"
                               step="1000">
                    </div>
                    @error('harga')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- STOK --}}
                <div class="col-md-6">
                    <label class="form-label">Stok</label>
                    <input type="number"
                           name="stok"
                           value="{{ old('stok', 0) }}"
                           class="form-control form-control-sm @error('stok') is-invalid @enderror"
                           min="0">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FOTO BARANG --}}
                <div class="col-md-6">
                    <label class="form-label">Foto Barang</label>
                    <input type="file"
                           name="foto_barang"
                           class="form-control form-control-sm @error('foto_barang') is-invalid @enderror"
                           accept="image/*">
                    <small class="text-muted">
                        Format JPG, PNG, atau WEBP maksimal 2MB.
                    </small>
                    @error('foto_barang')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- FOOTER --}}
        <div class="card-footer d-flex justify-content-end gap-2 barang-create-footer">
            <button type="reset" class="btn btn-light border">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </button>
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
        </div>

    </form>
</div>
@endsection
