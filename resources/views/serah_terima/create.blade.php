@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Form Serah Terima</h1>

<form method="POST" action="{{ route('serahterima.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih Peminjaman --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}" @selected(old('id_peminjaman')==$item->id_peminjaman)>
                        {{ $item->barang->nama_barang ?? 'Barang' }} - {{ $item->pengguna->nama ?? 'Pengguna' }}
                    </option>
                @endforeach
            </select>
            @error('id_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Pengguna Lama</label>
                <select name="pengguna_lama" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($pengguna as $item)
                        <option value="{{ $item->id_pengguna }}" @selected(old('pengguna_lama')==$item->id_pengguna)>{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('pengguna_lama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Pengguna Baru</label>
                <select name="pengguna_baru" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($pengguna as $item)
                        <option value="{{ $item->id_pengguna }}" @selected(old('pengguna_baru')==$item->id_pengguna)>{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('pengguna_baru')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
            @error('catatan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ url()->previous() }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
@endsection
