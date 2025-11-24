@extends('layouts.app')

@section('content')
@php
    $keluhanRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
    $peminjaman = $keluhan->peminjaman;

    $status = $peminjaman?->status;
    $badge = 'secondary';
    if ($status === 'berlangsung') $badge = 'info';
    elseif ($status === 'selesai') $badge = 'success';
    elseif ($status === 'ditolak') $badge = 'danger';

    $isVideo = $keluhan->foto_url
        && \Illuminate\Support\Str::of($keluhan->foto_url)->lower()->contains(['.mp4', '.mov', '.avi', '.webm']);
@endphp

<script>
    document.body.dataset.detailContext = 'keluhan';
</script>

<div
    class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-start align-items-md-center py-4 detail-overlay detail-overlay--keluhan"
    style="overflow-y:auto;z-index:1050;"
>
    <div class="container px-3 px-md-0 my-3 my-md-4">
        <div class="d-flex justify-content-center">
            <div
                class="card shadow-lg border-0 w-100"
                style="max-width: 860px; border-radius: 18px; max-height: calc(100vh - 80px); overflow-y: auto;"
            >
                <div class="card-body p-4 p-md-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="h4 mb-1">Detail Keluhan</h2>
                            <small class="text-muted">Keluhan #{{ $keluhan->id_keluhan }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route($keluhanRouteScope . '.keluhan.index') }}" class="btn btn-outline-secondary btn-sm">
                                Tutup
                            </a>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Mahasiswa</p>
                                <div class="fw-semibold mb-0">{{ $keluhan->pengguna->nama ?? '-' }}</div>
                                <div class="text-muted">{{ $keluhan->pengguna->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Barang</p>
                                <div class="fw-semibold mb-0">{{ $peminjaman?->barang->nama_barang ?? 'N/A' }}</div>
                                <div class="text-muted">{{ $peminjaman?->barang->kode_barang ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Keluhan</p>
                                <div class="fw-semibold mb-1">Deskripsi</div>
                                <div class="text-muted">{{ $keluhan->keluhan }}</div>
                                <div class="text-muted mt-3">
                                    Dilaporkan: {{ optional($keluhan->created_at)->format('d M Y H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 bg-white p-3 h-100">
                                <p class="text-muted small fw-semibold mb-2">Status Peminjaman</p>
                                <div class="mb-3">
                                    <span class="badge bg-{{ $badge }} px-3 py-2 text-capitalize">
                                        {{ $status ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="text-muted small mb-1">Tanggal Pinjam</div>
                                <div class="fw-semibold mb-3">
                                    {{ optional($peminjaman?->tanggal_pinjam)->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-muted small mb-1">Tanggal Kembali</div>
                                <div class="fw-semibold">
                                    {{ optional($peminjaman?->tanggal_kembali)->format('d M Y') ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($keluhan->foto_url)
                        <div class="border rounded-3 bg-white p-3 mb-3">
                            <p class="text-muted small fw-semibold mb-2">Lampiran Bukti Keluhan</p>
                            @if($isVideo)
                                <video controls class="w-100 rounded border" style="max-height: 420px;">
                                    <source src="{{ $keluhan->foto_url }}">
                                    Browser tidak mendukung pemutar video.
                                </video>
                            @else
                                <img src="{{ $keluhan->foto_url }}" alt="Lampiran keluhan" class="img-fluid rounded" style="max-height: 420px; object-fit: cover;">
                            @endif
                        </div>
                    @endif

                    @auth
                        @if(auth()->user()->role === 'petugas')
                            <div class="border rounded-3 bg-white p-3">
                                <p class="text-muted small fw-semibold mb-2">Aksi Petugas</p>
                                <div class="text-muted mb-2">Keluhan ini dapat diproses untuk perbaikan atau service.</div>
                                {{-- Tambahkan tombol aksi petugas di sini jika diperlukan --}}
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
