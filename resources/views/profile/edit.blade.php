@extends('layouts.app')

@section('content')
@php
    $user = $user ?? auth()->user();
@endphp

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-1">Edit Profil</h5>
                <small class="text-muted">Perbarui nama dan email Anda</small>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success mt-3">Profil berhasil diperbarui.</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="mt-3">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label class="form-label form-label-modern" for="name">Nama</label>
                        <input type="text" class="form-control form-control-modern @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern" for="email">Email</label>
                        <input type="email" class="form-control form-control-modern @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-modern btn-modern-primary">Simpan Perubahan</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-1">Edit Password</h5>
                <small class="text-muted">Gunakan password minimal 8 karakter</small>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success mt-3">Password berhasil diperbarui.</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="mt-3">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label class="form-label form-label-modern" for="current_password">Password Lama</label>
                        <input type="password" class="form-control form-control-modern @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern" for="password">Password Baru</label>
                        <input type="password" class="form-control form-control-modern @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern" for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control form-control-modern" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-modern btn-modern-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
