@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0">Pengembalian Barang</h1>
    <a href="{{ route('pengembalian.create') }}" class="btn btn-primary">Input Pengembalian</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $item)
                    <tr>
                        <td>{{ $item->id_pengembalian }}</td>
                        <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->peminjaman->pengguna->nama ?? '-' }}</td>
                        <td>{{ optional($item->waktu_pengembalian)->format('d M Y H:i') }}</td>
                        <td>{{ ucfirst($item->status ?? 'selesai') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada pengembalian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
