@extends('layouts.app')

@section('content')

{{-- ===== STYLE KHUSUS HALAMAN FORM KELUHAN ===== --}}
<style>
    :root {
        --sipkam-complaint-accent: var(--primary-dark);
        --sipkam-complaint-accent-soft: rgba(79,70,229,0.12);
    }

    /* Latar full screen, ikut tema global */
    .sipkam-complaint-form-page {
        min-height: 100vh;
        margin: -24px -32px -40px -32px;   /* tarik keluar padding layout */
        padding: 24px 32px 40px;           /* tetap ada ruang dari tepi */
        display: block;                    /* biar wrapper full width */
        background: linear-gradient(135deg, #d7f3f4 0%, #e8f2ff 40%, #dde9fb 100%);
        background-size: 300% 300%;
        animation: sipkamGradientLight 10s ease infinite;
        color: #0f172a;
    }

    body.sipkam-light .sipkam-complaint-form-page {
        background: var(--sipkam-bg-light, linear-gradient(135deg, #d7f3f4 0%, #e8f2ff 40%, #dde9fb 100%));
    }

    body.sipkam-dark .sipkam-complaint-form-page {
        background: var(--sipkam-bg-dark, radial-gradient(circle at top, #020617 0%, #020617 40%, #020617 100%));
        background-size: 200% 200%;
        animation: sipkamGradientDark 12s ease infinite;
        color: #e5e7eb;
    }

    @keyframes sipkamGradientLight {
        0%   { background-position: 0% 0%; }
        50%  { background-position: 100% 100%; }
        100% { background-position: 0% 0%; }
    }

    @keyframes sipkamGradientDark {
        0%   { background-position: 50% 0%; }
        50%  { background-position: 50% 100%; }
        100% { background-position: 50% 0%; }
    }

    .sipkam-complaint-form-shell {
        width: 100%;
        max-width: 100%;   /* mentok full kanan kiri 100% */
    }

    /* HEADER */
    .sipkam-complaint-form-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .sipkam-complaint-form-back {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    body.sipkam-light .sipkam-complaint-form-back {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 8px 18px rgba(148,163,184,0.45);
    }

    body.sipkam-dark .sipkam-complaint-form-back {
        background: #020617;
        color: #e5e7eb;
        box-shadow: 0 10px 24px rgba(0,0,0,0.9);
        border: 1px solid rgba(31,41,55,0.9);
    }

    .sipkam-complaint-form-back:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(15,23,42,0.55);
    }

    .sipkam-complaint-form-title {
        font-weight: 700;
        letter-spacing: 0.03em;
        margin-bottom: 0.1rem;
    }

    .sipkam-complaint-form-subtitle {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    /* CARD FORM */
    .sipkam-complaint-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
    }

    body.sipkam-light .sipkam-complaint-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(148,163,184,0.35);
        box-shadow: 0 24px 56px rgba(148,163,184,0.5);
    }

    body.sipkam-dark .sipkam-complaint-card {
        background: radial-gradient(circle at top left,#020617,#020617 55%,#020617 100%);
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 26px 70px rgba(0,0,0,0.95);
    }

    .sipkam-complaint-card .card-body {
        padding: 1.75rem 1.9rem 1.4rem;
        position: relative;
        z-index: 1;
    }

    .sipkam-complaint-card .card-footer {
        padding: 0.9rem 1.9rem 1.3rem;
        border-top: 1px solid rgba(148,163,184,0.25);
        position: relative;
        z-index: 1;
    }

    body.sipkam-dark .sipkam-complaint-card .card-footer {
        border-color: rgba(31,41,55,0.85);
        background: transparent; /* supaya nggak putih di dark mode */
    }

    /* Label & input */
    .sipkam-complaint-card .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
    }

    .sipkam-complaint-card .form-control,
    .sipkam-complaint-card .form-select {
        border-radius: 0.75rem;
        border-width: 1px;
        padding: 0.55rem 0.9rem;
        font-size: 0.9rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, color 0.15s ease;
    }

    body.sipkam-light .sipkam-complaint-card .form-control,
    body.sipkam-light .sipkam-complaint-card .form-select {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-complaint-card .form-control,
    body.sipkam-dark .sipkam-complaint-card .form-select {
        background: rgba(15,23,42,0.9);
        border-color: rgba(31,41,55,0.9);
        color: #e5e7eb;
    }

    .sipkam-complaint-card .form-control:focus,
    .sipkam-complaint-card .form-select:focus {
        outline: none;
        box-shadow: 0 0 0 1px rgba(251,191,36,0.45), 0 0 0 4px rgba(251,191,36,0.22);
        border-color: var(--sipkam-complaint-accent);
    }

    body.sipkam-light .sipkam-complaint-card .form-control::placeholder,
    body.sipkam-light .sipkam-complaint-card textarea.form-control::placeholder {
        color: #9ca3af;
    }

    body.sipkam-dark .sipkam-complaint-card .form-control::placeholder,
    body.sipkam-dark .sipkam-complaint-card textarea.form-control::placeholder {
        color: #6b7280;
    }

    .sipkam-complaint-card .text-muted {
        font-size: 0.8rem;
    }

    /* Tombol footer */
    .sipkam-complaint-card .btn {
        border-radius: 999px;
        padding-inline: 1.4rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .btn-complaint-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        border: none !important;
        color: #ffffff !important;
        box-shadow: 0 12px 30px rgba(79,70,229,0.25);
        font-weight: 600;
    }

    .btn-complaint-primary:hover {
        color: #ffffff !important;
        filter: brightness(1.05);
    }

    body.sipkam-dark .btn-complaint-primary {
        box-shadow: 0 12px 32px rgba(99,102,241,0.35);
    }

    body.sipkam-dark .sipkam-complaint-card .btn-light {
        background: transparent;
        color: #e5e7eb;
        border-color: rgba(148,163,184,0.6);
    }

    .sipkam-complaint-card .btn-light:hover {
        background: rgba(148,163,184,0.15);
    }

    /* Upgrade btn-warning tapi tetap pakai class yang sama */
    body.sipkam-light .sipkam-complaint-card .btn-warning {
        border: none;
        background: linear-gradient(135deg,#fbbf24,#f97316);
        color: #111827;
        box-shadow: 0 10px 26px rgba(251,191,36,0.65);
    }

    body.sipkam-dark .sipkam-complaint-card .btn-warning {
        border: none;
        background: linear-gradient(135deg,#fbbf24,#fde047);
        color: #0f172a;
        box-shadow: 0 12px 30px rgba(251,191,36,0.8);
    }

    .sipkam-complaint-card .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 42px rgba(251,191,36,0.9);
    }

    @media (max-width: 767.98px) {
        .sipkam-complaint-form-page {
            margin: -16px -16px -24px -16px;
            padding: 20px 16px 28px;
        }
        .sipkam-complaint-card .card-body,
        .sipkam-complaint-card .card-footer {
            padding-inline: 1.1rem;
        }
    }
</style>

<div class="sipkam-complaint-form-page">
    <div class="sipkam-complaint-form-shell">

        {{-- HEADER (judul) --}}
        <div class="sipkam-complaint-form-header">
            <div>
                <h1 class="sipkam-complaint-form-title h4 mb-0">
                    Form Keluhan Peminjaman
                </h1>
                <div class="sipkam-complaint-form-subtitle">
                    Sampaikan keluhan terkait peminjaman beserta bukti pendukung jika diperlukan.
                </div>
            </div>
        </div>

        {{-- FORM: LOGIKA PERSIS SEPERTI SEBELUMNYA, HANYA DIBERI CLASS UNTUK STYLING --}}
        <form method="POST"
              action="{{ route('mahasiswa.keluhan.store') }}"
              class="sipkam-complaint-card card border-0 shadow-sm"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Peminjaman</label>
                    <select name="id_peminjaman" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @forelse($peminjaman as $item)
                            <option value="{{ $item->id_peminjaman }}" @selected(old('id_peminjaman')==$item->id_peminjaman)>
                                {{ $item->barang->nama_barang ?? 'Barang' }} - {{ $item->pengguna->nama ?? 'Pengguna' }}
                            </option>
                        @empty
                            <option value="">Tidak ada peminjaman yang sedang berlangsung.</option>
                        @endforelse
                    </select>
                    @error('id_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
                    @if($peminjaman->isEmpty())
                        <small class="text-muted d-block mt-1">Anda hanya dapat melaporkan keluhan untuk peminjaman yang sedang berlangsung.</small>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Detail Keluhan</label>
                    <textarea name="keluhan" rows="4" class="form-control" required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Lampiran Bukti (Foto/Video/Audio) <span class="text-danger">*</span></label>
                    <input type="file" name="foto_keluhan" class="form-control" accept="image/*,video/*,.mp3" required>
                    <small class="text-muted d-block mt-1">
                        Wajib unggah bukti. Format gambar (JPG, PNG, WEBP), video (MP4, MOV, WEBM), atau audio (MP3), maks 20MB.
                    </small>
                    @error('foto_keluhan')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="card-footer text-end bg-white">
                <a href="{{ route('mahasiswa.keluhan.index') }}" class="btn btn-light">Batal</a>
                <button class="btn btn-complaint-primary" type="submit">Kirim Keluhan</button>
            </div>
        </form>

    </div>
</div>
@endsection
