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
                @php $payload = json_decode($qr->payload, true); @endphp
                <div class="text-start mt-3">
                    <div class="fw-semibold mb-2">Payload</div>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li>Kode transaksi: {{ $payload['kode_transaksi'] ?? '-' }}</li>
                        <li>ID Peminjaman: {{ $payload['id_peminjaman'] ?? '-' }}</li>
                        <li>ID Mahasiswa: {{ $payload['id_mahasiswa'] ?? '-' }}</li>
                        <li>ID Barang: {{ $payload['id_barang'] ?? '-' }}</li>
                    </ul>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
