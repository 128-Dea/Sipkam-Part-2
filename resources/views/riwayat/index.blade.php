@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Riwayat Peminjaman</h1>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                    <tr>
                        <td>{{ $item->id_peminjaman }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->waktu_awal)->format('d M Y') }}</td>
                        <td><a href="{{ route('riwayat.show', $item->id_peminjaman) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Riwayat masih kosong.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
