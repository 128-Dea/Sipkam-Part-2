@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h1 class="h4 mb-3">QR Code</h1>
                <div class="mb-3">{!! $qrImage !!}</div>
                <h5 class="fw-semibold">{{ $qr->qr_code }}</h5>
                <p class="text-muted mb-1">Jenis Transaksi: {{ ucfirst($qr->jenis_transaksi) }}</p>
                <p class="text-muted">Status: {{ $qr->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
