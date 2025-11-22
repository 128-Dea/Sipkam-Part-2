@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $role = $user?->role ?? '-';
    $email = $user?->email ?? '-';
    $phone = $user?->phone ?? $user?->nomor_hp ?? '-';
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="h4 mb-1">Profil Pengguna</h1>
                        <small class="text-muted">Informasi dasar akun Anda</small>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase">{{ $role }}</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted mb-1">Nama</div>
                            <div class="fw-semibold">{{ $user?->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted mb-1">Email</div>
                            <div class="fw-semibold">{{ $email }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted mb-1">Nomor Telepon</div>
                            <div class="fw-semibold">{{ $phone }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted mb-1">Peran</div>
                            <div class="fw-semibold text-capitalize">{{ $role }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Keamanan Akun</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-modern btn-modern-primary">
                        Edit Profil & Password
                    </a>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        Logout
                    </button>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Tips Keamanan</h6>
                <ul class="text-muted small mb-0">
                    <li>Gunakan password minimal 8 karakter.</li>
                    <li>Hindari menggunakan password yang sama di layanan lain.</li>
                    <li>Periksa ulang sebelum keluar dari akun.</li>
                </ul>
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
