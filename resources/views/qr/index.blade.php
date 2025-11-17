@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">QR Code Transaksi</h1>

<div class="row g-4">
    @forelse($qr as $item)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $item->qr_code }}" alt="QR" class="img-fluid mb-3">
                    <h6 class="fw-semibold">{{ $item->qr_code }}</h6>
                    <p class="text-muted mb-1">Jenis: {{ ucfirst($item->jenis_transaksi) }}</p>
                    <a href="{{ route('qr.show', $item->id_qr) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">Belum ada QR Code.</div>
    @endforelse
</div>
@endsection
