@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Tambah Barang</h1>
    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<form method="POST" action="{{ route('barang.store') }}" class="card border-0 shadow-sm" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-control" required>
            @error('nama_barang')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach($kategori as $item)
                    <option value="{{ $item->id_kategori }}" @selected(old('id_kategori')==$item->id_kategori)>{{ $item->nama_kategori }}</option>
                @endforeach
            </select>
            @error('id_kategori')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" class="form-control" required>
            @error('kode_barang')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" value="{{ old('harga') }}" class="form-control" min="0" step="1000">
                @error('harga')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="tersedia" @selected(old('status')==='tersedia')>Tersedia</option>
                    <option value="dipinjam" @selected(old('status')==='dipinjam')>Dipinjam</option>
                    <option value="dalam_service" @selected(old('status')==='dalam_service')>Dalam Service</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Barang</label>
            <input type="file" name="foto_barang" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Format JPG, PNG, atau WEBP maksimal 2MB.</small>
            @error('foto_barang')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <button class="btn btn-primary" type="submit">Simpan</button>
    </div>
</form>
@endsection
