@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Tambah Denda</h1>
<form method="POST" action="{{ route('petugas.denda.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjam --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}" @selected(old('id_peminjaman')==$item->id_peminjaman)>
                        {{ $item->pengguna->nama ?? 'Pengguna' }} - {{ $item->barang->nama_barang ?? 'Barang' }}
                    </option>
                @endforeach
            </select>
            @error('id_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Denda</label>
            <input type="text" name="jenis" value="{{ old('jenis') }}" class="form-control" required>
            @error('jenis')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Total Denda</label>
                <input type="number" name="total_denda" value="{{ old('total_denda') }}" class="form-control" min="0" required>
                @error('total_denda')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control">
            </div>
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ route('petugas.denda.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </div>
</form>
@endsection
