@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0">Denda Peminjaman</h1>
    <a href="{{ route('denda.create') }}" class="btn btn-primary">Tambah Denda</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Peminjaman</th>
                    <th>Jenis</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($denda as $item)
                    <tr>
                        <td>{{ $item->id_denda }}</td>
                        <td>{{ $item->peminjaman->pengguna->nama ?? '-' }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td>Rp {{ number_format($item->total_denda,0,',','.') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status_pembayaran === 'sudah' ? 'success' : 'danger' }}">
                                {{ ucfirst($item->status_pembayaran) }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('denda.update', $item->id_denda) }}" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status_pembayaran" class="form-select form-select-sm">
                                    <option value="belum" @selected($item->status_pembayaran==='belum')>Belum</option>
                                    <option value="sudah" @selected($item->status_pembayaran==='sudah')>Sudah</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada denda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
