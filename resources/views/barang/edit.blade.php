@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Barang</h1>
    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<form method="POST" action="{{ route('barang.update', $barang->id_barang) }}" class="card border-0 shadow-sm" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                @foreach($kategori as $item)
                    <option value="{{ $item->id_kategori }}" @selected(old('id_kategori', $barang->id_kategori)==$item->id_kategori)>{{ $item->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="form-control" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" value="{{ old('harga', $barang->harga) }}" class="form-control" min="0" step="1000">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['tersedia'=>'Tersedia','dipinjam'=>'Dipinjam','dalam_service'=>'Dalam Service'] as $value=>$label)
                        <option value="{{ $value }}" @selected(old('status', $barang->status)==$value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Barang</label>
            <input type="file" name="foto_barang" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah foto.</small>
            @error('foto_barang')<small class="text-danger">{{ $message }}</small>@enderror
            @if($barang->foto_url)
                <div class="mt-3">
                    <p class="text-muted mb-2">Foto saat ini:</p>
                    <img src="{{ $barang->foto_url }}" alt="Foto {{ $barang->nama_barang }}" class="img-fluid rounded border" style="max-height: 220px; object-fit: cover;">
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <button class="btn btn-primary" type="submit">Perbarui</button>
    </div>
</form>
@endsection
