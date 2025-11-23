@extends('layouts.app')

@section('content')

{{-- ====== STYLE: FORM PEMINJAMAN FULL WIDTH + GRADIENT + DARK MODE ====== --}}
<style>
    /* Wrapper halaman peminjaman: FULL, mentok kanan—kiri—atas—bawah area main */
    .peminjaman-full-bg {
        margin: -1.5rem -1.5rem -1.5rem;
        padding: 1.5rem 1.5rem 2rem;
        min-height: calc(100vh - 72px);
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #0ea5e9 100%);
    }

    @media (max-width: 768px) {
        .peminjaman-full-bg {
            margin: -1rem;
            padding: 1rem 1rem 1.5rem;
            min-height: auto;
        }
    }

    /* DARK MODE: background hitam */
    body.sipkam-dark .peminjaman-full-bg {
        background: #020617;
    }

    /* Header di atas form */
    .peminjaman-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        color: #f9fafb;
    }

    .peminjaman-header-bar small {
        opacity: .9;
        font-size: 0.78rem;
    }

    .peminjaman-header-bar h1 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: .15rem;
    }

    .peminjaman-header-bar p {
        margin-bottom: 0;
        font-size: .82rem;
        opacity: .9;
    }

    .peminjaman-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* DARK MODE: header font hijau */
    body.sipkam-dark .peminjaman-header-bar small,
    body.sipkam-dark .peminjaman-header-bar h1,
    body.sipkam-dark .peminjaman-header-bar p {
        color: #bbf7d0;
    }
    body.sipkam-dark .peminjaman-header-icon {
        background: #020617;
        border-color: rgba(34, 197, 94, 0.7);
    }

    /* FORM utama: FULL WIDTH, gradasi biru–ungu */
    form.peminjaman-form {
        width: 100%;
        border-radius: 18px;
        border: none;
        overflow: hidden;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #0ea5e9 100%);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.25);
        color: #0f172a;
    }

    /* DARK MODE: form hitam dengan aksen hijau */
    body.sipkam-dark form.peminjaman-form {
        background: #020617;
        border: 1px solid rgba(34, 197, 94, 0.35);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.9);
        color: #e5e7eb;
    }

    /* Body & footer form transparan supaya gradasi/warna dasar terlihat */
    form.peminjaman-form .card-body,
    form.peminjaman-form .card-footer {
        background: transparent;
    }

    form.peminjaman-form .card-body {
        padding: 1.5rem 1.75rem 1.25rem;
    }

    form.peminjaman-form .card-footer {
        padding: .9rem 1.75rem 1.1rem;
        border-top: 1px solid rgba(148, 163, 184, 0.35);
    }

    /* SECTION pemecah form */
    .sipkam-section + .sipkam-section {
        border-top: 1px dashed rgba(226, 232, 240, 0.8);
        margin-top: 1rem;
        padding-top: 1rem;
    }
    body.sipkam-dark .sipkam-section + .sipkam-section {
        border-top-color: rgba(55, 65, 81, 0.9);
    }

    .sipkam-section-title {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: .25rem;
        display: flex;
        align-items: center;
        gap: .35rem;
        color: #e5e7eb;
    }

    .sipkam-section-title i {
        font-size: .8rem;
        color: #e0e7ff;
    }

    .sipkam-section-subtitle {
        font-size: .82rem;
        color: #e5e7f5;
        margin-bottom: .75rem;
        opacity: .9;
    }

    /* DARK MODE: judul & subtitle hijau */
    body.sipkam-dark .sipkam-section-title {
        color: #bbf7d0;
    }
    body.sipkam-dark .sipkam-section-title i {
        color: #22c55e;
    }
    body.sipkam-dark .sipkam-section-subtitle {
        color: #86efac;
    }

    /* Label & input */
    form.peminjaman-form .form-label {
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #e5e7eb;
    }

    form.peminjaman-form .form-select,
    form.peminjaman-form .form-control,
    form.peminjaman-form textarea {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: .9rem;
        padding: .6rem .75rem;
        background-color: #ffffff;
    }

    /* DARK MODE: input gelap, border hijau, font terang */
    body.sipkam-dark form.peminjaman-form .form-label {
        color: #bbf7d0;
    }
    body.sipkam-dark form.peminjaman-form .form-select,
    body.sipkam-dark form.peminjaman-form .form-control,
    body.sipkam-dark form.peminjaman-form textarea {
        background-color: #020617;
        border-color: rgba(34, 197, 94, 0.6);
        color: #e5e7eb;
    }
    body.sipkam-dark form.peminjaman-form .form-select::placeholder,
    body.sipkam-dark form.peminjaman-form .form-control::placeholder,
    body.sipkam-dark form.peminjaman-form textarea::placeholder {
        color: #6b7280;
    }

    form.peminjaman-form .form-select:focus,
    form.peminjaman-form .form-control:focus,
    form.peminjaman-form textarea:focus {
        border-color: #c7d2fe;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.5);
    }

    body.sipkam-dark form.peminjaman-form .form-select:focus,
    body.sipkam-dark form.peminjaman-form .form-control:focus,
    body.sipkam-dark form.peminjaman-form textarea:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.4);
    }

    .sipkam-help-text {
        font-size: .78rem;
        color: #e0e7ff;
        margin-top: 4px;
        opacity: .8;
    }
    body.sipkam-dark .sipkam-help-text {
        color: #86efac;
    }

    /* Tombol */
    form.peminjaman-form .btn-light {
        border-radius: 999px;
        padding-inline: 18px;
    }

    form.peminjaman-form .btn-primary {
        border-radius: 999px;
        padding-inline: 22px;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border-color: transparent;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
    }

    form.peminjaman-form .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 40px rgba(79, 70, 229, 0.6);
    }

    /* DARK MODE: tombol utama hijau neon */
    body.sipkam-dark form.peminjaman-form .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        box-shadow: 0 14px 36px rgba(34, 197, 94, 0.55);
        color: #020617;
    }
</style>

<div class="peminjaman-full-bg">

    {{-- HEADER KECIL DI ATAS FORM --}}
    <div class="peminjaman-header-bar">
        <div>
            <small>Mahasiswa / Peminjaman</small>
            <h1 class="mb-0">Form Peminjaman Barang</h1>
            <p>Isi detail peminjaman barang kampus dengan lengkap dan jelas.</p>
        </div>
        <div class="peminjaman-header-icon">
            <i class="fas fa-qrcode text-white"></i>
        </div>
    </div>

    {{-- ====== FORM ASLI (LOGIKA TIDAK DIUBAH) + DIPECAH SECTION ====== --}}
    <form method="POST"
          action="{{ route('mahasiswa.peminjaman.store') }}"
          class="card border-0 peminjaman-form">
        @csrf

        <div class="card-body">

            {{-- SECTION 1: DETAIL BARANG --}}
            <div class="sipkam-section">
                <div class="sipkam-section-title">
                    <i class="fas fa-box-open"></i> Detail Barang
                </div>
                <div class="sipkam-section-subtitle">
                    Pilih barang yang ingin Anda pinjam.
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Barang</label>
                    <select name="id_barang" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->id_barang }}" @selected(old('id_barang', $prefillBarangId ?? null) == $item->id_barang)>
                                {{ $item->nama_barang }} (Stok: {{ $item->stok ?? '-' }} | Status: {{ ucfirst($item->status) }})
                            </option>
                        @endforeach
                    </select>
                    <div class="sipkam-help-text">
                        Pastikan stok masih tersedia sebelum mengajukan peminjaman.
                    </div>
                    @error('id_barang')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            {{-- SECTION 2: PERIODE PEMINJAMAN --}}
            <div class="sipkam-section">
                <div class="sipkam-section-title">
                    <i class="fas fa-clock"></i> Periode Peminjaman
                </div>
                <div class="sipkam-section-subtitle">
                    Tentukan tanggal dan jam mulai sampai selesai peminjaman.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_awal"
                               value="{{ old('waktu_awal') ? \Carbon\Carbon::parse(old('waktu_awal'))->format('Y-m-d\\TH:i') : '' }}"
                               step="60"
                               class="form-control" required>
                        @error('waktu_awal')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_akhir"
                               value="{{ old('waktu_akhir') ? \Carbon\Carbon::parse(old('waktu_akhir'))->format('Y-m-d\\TH:i') : '' }}"
                               step="60"
                               class="form-control" required>
                        @error('waktu_akhir')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 3: KETERANGAN --}}
            <div class="sipkam-section">
                <div class="sipkam-section-title">
                    <i class="fas fa-note-sticky"></i> Keterangan
                </div>
                <div class="sipkam-section-subtitle">
                    Jelaskan kebutuhan peminjaman Anda secara singkat.
                </div>

                <div class="mb-2">
                    <label class="form-label">Alasan Peminjaman</label>
                    <textarea name="alasan" rows="3" class="form-control" placeholder="Contoh: Kegiatan UKM, praktikum, ujian, dsb.">{{ old('alasan') }}</textarea>
                    @error('alasan')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-qrcode"></i>
                <span>Simpan &amp; Generate QR</span>
            </button>
        </div>
    </form>
</div>

@endsection
