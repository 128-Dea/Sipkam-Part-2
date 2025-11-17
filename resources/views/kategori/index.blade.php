@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0">Kategori Barang</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $item)
                    <tr>
                        <td>{{ $item->id_kategori }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td class="text-end">
                            <a href="{{ route('kategori.edit', $item->id_kategori) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
