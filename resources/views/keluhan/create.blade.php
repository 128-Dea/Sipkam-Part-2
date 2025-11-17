@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Form Keluhan Peminjaman</h1>

<form method="POST" action="{{ route('keluhan.store') }}" class="card border-0 shadow-sm" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Pilih Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}" @selected(old('id_peminjaman')==$item->id_peminjaman)>
                        {{ $item->barang->nama_barang ?? 'Barang' }} - {{ $item->pengguna->nama ?? 'Pengguna' }}
                    </option>
                @endforeach
            </select>
            @error('id_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Detail Keluhan</label>
            <textarea name="keluhan" rows="4" class="form-control" required>{{ old('keluhan') }}</textarea>
            @error('keluhan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Bukti Keluhan (Opsional)</label>
            <input type="file" name="foto_keluhan" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Format JPG, PNG, atau WEBP maksimal 2MB. Upload foto sebagai bukti keluhan.</small>
            @error('foto_keluhan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ route('keluhan.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-warning" type="submit">Kirim Keluhan</button>
    </div>
</form>
@endsection
