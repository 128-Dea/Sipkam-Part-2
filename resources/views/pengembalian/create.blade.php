@extends('layouts.app')

@section('content')

{{-- ======================= STYLE HALAMAN PENGEMBALIAN ======================= --}}
<style>
    /* Warna hijau original */
    :root {
        --sipkam-return-accent: #22c55e;
        --sipkam-return-accent-soft: rgba(34,197,94,0.35);
    }

    /* Background halaman */
    .sipkam-return-form-page {
        min-height: 100vh;
        margin: -24px -32px -40px -32px;
        padding: 32px 32px 40px;
        display: block;
    }

    body.sipkam-light .sipkam-return-form-page {
        background: linear-gradient(135deg,#e0f2fe 0%,#f9fafb 40%,#dcfce7 100%);
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-return-form-page {
        background: radial-gradient(circle at top,#020617 0%,#020617 40%,#020617 100%);
        color: #e5e7eb;
    }

    .sipkam-return-form-shell {
        width: 100%;
        max-width: 100%;
        padding: 0;
    }

    /* Header */
    .sipkam-return-form-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .sipkam-return-form-back {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        cursor: pointer;
        transition: 0.15s ease;
    }

    body.sipkam-light .sipkam-return-form-back {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 8px 18px rgba(148,163,184,0.45);
    }

    body.sipkam-dark .sipkam-return-form-back {
        background: #020617;
        color: #e5e7eb;
        box-shadow: 0 10px 24px rgba(0,0,0,0.9);
        border: 1px solid rgba(31,41,55,0.9);
    }

    /* Card */
    .sipkam-return-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
    }

    body.sipkam-light .sipkam-return-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(148,163,184,0.35);
        box-shadow: 0 24px 56px rgba(148,163,184,0.55);
    }

    body.sipkam-dark .sipkam-return-card {
        background: radial-gradient(circle at top left,#020617,#020617 55%,#020617 100%);
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 26px 70px rgba(0,0,0,0.95);
    }

    /* Glow hijau dekorasi */
    .sipkam-return-card::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -40px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: radial-gradient(circle,var(--sipkam-return-accent-soft),transparent 70%);
        filter: blur(2px);
        opacity: 0.75;
        pointer-events: none;
    }

    /* Padding card */
    .sipkam-return-card .card-body {
        padding: 1.75rem 1.9rem 1.4rem;
        position: relative;
        z-index: 1;
    }

    .sipkam-return-card .card-footer {
        padding: 0.9rem 1.9rem 1.3rem;
        border-top: 1px solid rgba(148,163,184,0.25);
        background: white;
        position: relative;
        z-index: 1;
    }

    /* FIX: Semua input/select/textarea harus PUTIH */
    .sipkam-return-card input,
    .sipkam-return-card select,
    .sipkam-return-card textarea {
        background: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }

    /* Mode gelap tetap putih */
    body.sipkam-dark .sipkam-return-card input,
    body.sipkam-dark .sipkam-return-card select,
    body.sipkam-dark .sipkam-return-card textarea {
        background: #ffffff !important;
        color: #0f172a !important;
    }

    /* Tombol */
    .sipkam-return-card .btn {
        border-radius: 999px;
        padding-inline: 1.4rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    @media (max-width: 767.98px) {
        .sipkam-return-form-page {
            margin: -16px -16px -24px -16px;
            padding: 20px 16px 28px;
        }
    }
</style>

{{-- ============================ HALAMAN ============================ --}}
<div class="sipkam-return-form-page">
    <div class="sipkam-return-form-shell">

        {{-- HEADER --}}
        <div class="sipkam-return-form-header">
            <button type="button" class="sipkam-return-form-back" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="sipkam-return-form-title h4 mb-0">Form Pengembalian</h1>
                <div class="sipkam-return-form-subtitle">Lengkapi detail pengembalian barang.</div>
            </div>
        </div>

        {{-- ============================ FORM ============================ --}}
        <form method="POST"
              action="{{ route('mahasiswa.pengembalian.store') }}"
              class="sipkam-return-card card border-0 shadow-sm">
            @csrf

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- PILIH PEMINJAMAN --}}
                <div class="mb-3">
                    <label class="form-label">Peminjaman</label>
                    <select name="id_peminjaman" class="form-select" required>
                        <option value="">-- Pilih peminjaman --</option>
                        @foreach($peminjaman as $item)
                            <option value="{{ $item->id_peminjaman }}"
                                {{ old('id_peminjaman') == $item->id_peminjaman ? 'selected' : '' }}>
                                {{ $item->barang->nama_barang ?? 'Barang' }}
                                @if(!empty($item->pengguna?->nama))
                                    - {{ $item->pengguna->nama }}
                                @endif
                                (Jatuh tempo: {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- WAKTU PENGEMBALIAN --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal & Jam Pengembalian</label>
                    <input type="datetime-local"
                           name="waktu_pengembalian"
                           class="form-control"
                           value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}"
                           required>
                </div>

                {{-- CATATAN --}}
                <div class="mb-3">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
                </div>

            </div>

            <div class="card-footer text-end bg-white">
                <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-light">Batal</a>
                <button class="btn btn-primary" type="submit">Kirim Pengembalian</button>
            </div>

        </form>

    </div>
</div>

@endsection
@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Form Pengembalian</h1>

<form method="POST" action="{{ route('mahasiswa.pengembalian.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjaman yang ingin dikembalikan --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}"
                        {{ old('id_peminjaman') == $item->id_peminjaman ? 'selected' : '' }}>
                        {{ $item->barang->nama_barang ?? 'Barang' }}
                        @if(!empty($item->pengguna?->nama))
                            - {{ $item->pengguna->nama }}
                        @endif
                        (Jatuh tempo: {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal & Jam Pengembalian</label>
            <input
                type="datetime-local"
                name="waktu_pengembalian"
                value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan (opsional)</label>
            <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
        </div>
    </div>

    <div class="card-footer text-end bg-white">
        <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary" type="submit">Kirim Pengembalian</button>
    </div>
</form>
@endsection
