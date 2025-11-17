@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Daftar Service Barang</h1>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Keluhan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($service as $item)
                    <tr>
                        <td>{{ $item->id_service }}</td>
                        <td>{{ $item->keluhan->peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->keluhan->keluhan ?? '-', 60) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'selesai' ? 'success' : ($item->status === 'diperbaiki' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('service.update', $item->id_service) }}" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" style="width: 140px;">
                                    @foreach(['mengantri'=>'Mengantri','diperbaiki'=>'Diperbaiki','selesai'=>'Selesai'] as $value=>$label)
                                        <option value="{{ $value }}" @selected($item->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-primary" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data service.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
