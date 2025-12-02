@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $role = $user?->role;

    $status = $peminjaman->status;
    $badge = 'secondary';
    if ($status === 'berlangsung') $badge = 'info';
    elseif ($status === 'selesai') $badge = 'success';
    elseif ($status === 'ditolak') $badge = 'danger';
    elseif ($status === 'booking') $badge = 'warning';
@endphp

<script>
    document.body.dataset.detailContext = 'peminjaman';
</script>

<div
    class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-start align-items-md-center py-4 detail-overlay detail-overlay--peminjaman"
    style="overflow-y:auto;"
>
    <div class="container px-3 px-md-0 my-3 my-md-4">
        <div class="d-flex justify-content-center">
            <div class="card shadow-lg border-0 w-100" style="max-width: 980px; border-radius: 18px;">
                <div class="card-body p-4 p-md-5" style="max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="h4 mb-1">Detail Transaksi</h2>
                            <small class="text-muted">Peminjaman #{{ $peminjaman->id_peminjaman }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            @if($role === 'mahasiswa' && $status === 'booking')
                                <form
                                    method="POST"
                                    action="{{ route('mahasiswa.peminjaman.cancel', $peminjaman->id_peminjaman) }}"
                                    onsubmit="return confirm('Batalkan booking ini? QR akan dinonaktifkan.');"
                                >
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Batalkan Booking
                                    </button>
                                </form>
                            @endif
                            <a
                                href="{{ $role === 'petugas'
                                        ? route('petugas.peminjaman.index')
                                        : route('mahasiswa.peminjaman.index') }}"
                                class="btn btn-outline-secondary btn-sm"
                            >
                                Tutup
                            </a>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Mahasiswa</p>
                                <div class="fw-semibold mb-0">{{ $peminjaman->pengguna->nama ?? '-' }}</div>
                                <div class="text-muted">{{ $peminjaman->pengguna->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Barang</p>
                                <div class="fw-semibold mb-0">{{ $peminjaman->barang->nama_barang ?? '-' }}</div>
                                <div class="text-muted">{{ $peminjaman->barang->kode_barang ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Periode</p>
                                <div class="text-muted mb-1">Mulai</div>
                                <div class="fw-semibold mb-3">
                                    {{ \Carbon\Carbon::parse($peminjaman->waktu_awal)->translatedFormat('d M Y H:i') }}
                                </div>
                                <div class="text-muted mb-1">Estimasi Kembali</div>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($peminjaman->waktu_akhir)->translatedFormat('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100 d-flex flex-column">
                                <p class="text-muted small fw-semibold mb-2">Status</p>
                                <div class="mb-3">
                                    <span class="badge bg-{{ $badge }} px-3 py-2 text-capitalize">
                                        {{ $status }}
                                    </span>
                                </div>
                                <p class="text-muted small fw-semibold mb-2">QR / Kode Transaksi</p>
                                <div class="fw-semibold">{{ $peminjaman->qr->qr_code ?? '-' }}</div>
                                <div class="mt-auto pt-3 text-center">
                                    @if ($peminjaman->qr)
                                        <img
                                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($peminjaman->qr->payload) }}"
                                            alt="QR Peminjaman"
                                            class="img-fluid"
                                            style="max-width: 160px;"
                                        >
                                    @else
                                        <p class="text-muted mb-0">QR belum tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-3 bg-white p-3">
                        <p class="text-muted small fw-semibold mb-2">riwayat Transaksi</p>
                        <div class="fw-semibold mb-1">
                            Peminjaman {{ $status }}.
                        </div>
                        <div class="text-muted">
                            riwayat perpanjangan: {{ $peminjaman->perpanjangan->count() }} kali. Keluhan: {{ $peminjaman->keluhan->count() }}.
                        </div>
                        <div class="text-muted mt-2">
                            Alasan: {{ $peminjaman->alasan ?: '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
