@extends('layouts.app')

@section('content')
@php
    $user  = auth()->user();
    $role  = $user?->role ?? '-';
    $email = $user?->email ?? '-';
    $phone = $user?->phone ?? $user?->nomor_hp ?? '-';
@endphp

<style>
    :root {
        /* Palet warna referensi */
        --profil-dark-1: #051F20;
        --profil-dark-2: #0B2B26;
        --profil-dark-3: #163832;
        --profil-mid:    #253547;
        --profil-soft:   #8EB69B;
        --profil-light:  #DAF1DE;
    }

    /* ============================
       WRAPPER & BACKGROUND MODE TERANG
       ============================ */
    .profil-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .profil-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media (max-width: 767.98px) {
        .profil-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .profil-inner {
            max-width: 100%;
        }
    }

    /* ============================
       CARD UTAMA PROFIL (default / terang)
       ============================ */
    .profil-card {
        border-radius: 20px;
        border: none;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.55);
    }

    .profil-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .profil-title h1 {
        font-weight: 650;
        letter-spacing: .03em;
        color: var(--profil-dark-1);
    }

    .profil-title small {
        color: #64748b;
    }

    .role-badge {
        background: rgba(5, 31, 32, 0.07);
        color: var(--profil-dark-1);
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 0.75rem;
        letter-spacing: .08em;
        border: 1px solid rgba(5, 31, 32, 0.1);
    }

    .profile-info-box {
        background: #f5fbf7;
        border-radius: 14px;
        padding: 12px 14px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        height: 100%;
    }

    .profile-info-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6b7280;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .profile-info-value {
        font-weight: 600;
        color: var(--profil-dark-2);
        word-break: break-word;
    }

    /* Kartu samping (keamanan & tips) – mode terang */
    .security-card,
    .tips-card {
        border-radius: 18px;
        border: none;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.45);
    }

    .security-card h5,
    .tips-card h6 {
        color: var(--profil-dark-2);
    }

    .tips-card ul li {
        margin-bottom: 3px;
    }

    /* Tombol */
    .btn-modern-primary {
        background: linear-gradient(135deg, var(--profil-soft), var(--profil-light));
        border: 1px solid rgba(142, 182, 155, 0.85);
        color: var(--profil-dark-1);
        font-weight: 600;
        border-radius: 999px;
    }

    .btn-modern-primary:hover {
        filter: brightness(1.03);
    }

    .btn-outline-danger {
        border-radius: 999px;
        font-weight: 500;
    }

    /* Modal logout */
    #logoutModal .modal-header {
        background: linear-gradient(135deg, var(--profil-dark-1), var(--profil-mid));
        color: #e9f7f0;
    }

    #logoutModal .btn-danger {
        background: #dc2626;
        border-color: #dc2626;
    }

    /* =========================================================
       MODE GELAP KHUSUS ROLE MAHASISWA (body.sipkam-dark)
       ========================================================= */
    body.sipkam-dark .profil-wrapper-mahasiswa {
        color: #a7f3d0;
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .profil-card,
    body.sipkam-dark .profil-wrapper-mahasiswa .security-card,
    body.sipkam-dark .profil-wrapper-mahasiswa .tips-card {
        background: #020617;
        border: 1px solid rgba(15, 23, 42, 0.9);
        box-shadow: 0 26px 70px rgba(0, 0, 0, 0.95);
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .profile-info-box {
        background: rgba(15, 23, 42, 0.95);
        border-color: rgba(34, 197, 94, 0.55);
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .profil-title h1,
    body.sipkam-dark .profil-wrapper-mahasiswa .role-badge,
    body.sipkam-dark .profil-wrapper-mahasiswa .profile-info-value,
    body.sipkam-dark .profil-wrapper-mahasiswa .security-card h5,
    body.sipkam-dark .profil-wrapper-mahasiswa .tips-card h6 {
        color: #bbf7d0;
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .profil-title small,
    body.sipkam-dark .profil-wrapper-mahasiswa .profile-info-label,
    body.sipkam-dark .profil-wrapper-mahasiswa .tips-card ul li {
        color: #6ee7b7;
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .role-badge {
        background: rgba(34, 197, 94, 0.16);
        border-color: rgba(34, 197, 94, 0.7);
    }

    /* Tombol neon di mode gelap mahasiswa */
    body.sipkam-dark .profil-wrapper-mahasiswa .btn-modern-primary {
        background: #22c55e;            /* hijau solid */
        border-color: #22c55e;
        color: #020617;
        box-shadow: 0 0 24px rgba(34, 197, 94, 0.85);
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .btn-modern-primary:hover {
        filter: brightness(1.08);
        box-shadow: 0 0 34px rgba(34, 197, 94, 1);
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .btn-outline-danger {
        border-color: #f97373;
        color: #fecaca;
    }

    body.sipkam-dark .profil-wrapper-mahasiswa .btn-outline-danger:hover {
        background: #ef4444;
        color: #020617;
    }

    /* Modal logout tetap gelap tapi sedikit neon */
    body.sipkam-dark .profil-wrapper-mahasiswa #logoutModal .modal-header {
        background: linear-gradient(135deg, #020617, #064e3b);
        color: #bbf7d0;
    }
</style>

{{-- Tambahkan class "profil-wrapper-mahasiswa" kalau rolenya mahasiswa --}}
<div class="profil-wrapper {{ strtolower($role) === 'mahasiswa' ? 'profil-wrapper-mahasiswa' : '' }}">
    <div class="profil-inner">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card profil-card h-100">
                    <div class="card-body">
                        <div class="profil-card-header">
                            <div class="profil-title">
                                <h1 class="h4 mb-1">Profil Pengguna</h1>
                                <small>Informasi dasar akun Anda</small>
                            </div>
                            <span class="role-badge text-uppercase">{{ $role }}</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="profile-info-box">
                                    <div class="profile-info-label">Nama</div>
                                    <div class="profile-info-value">{{ $user?->name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-info-box">
                                    <div class="profile-info-label">Email</div>
                                    <div class="profile-info-value">{{ $email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-info-box">
                                    <div class="profile-info-label">Nomor Telepon</div>
                                    <div class="profile-info-value">{{ $phone }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-info-box">
                                    <div class="profile-info-label">Peran</div>
                                    <div class="profile-info-value text-capitalize">{{ $role }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card security-card mb-3">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Keamanan Akun</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-modern-primary">
                                Edit Profil & Password
                            </a>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                Logout
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card tips-card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-2">Tips Keamanan</h6>
                        <ul class="text-muted small mb-0">
                            <li>1. Gunakan password minimal 8 karakter.</li>
                            <li>2. Hindari menggunakan password yang sama di layanan lain.</li>
                            <li>3. Periksa ulang sebelum keluar dari akun.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin keluar dari akun?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
