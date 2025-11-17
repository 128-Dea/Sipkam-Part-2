@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Ajukan Perpanjangan</h1>

<form method="POST" action="{{ route('perpanjangan.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjaman --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}" @selected(old('id_peminjaman')==$item->id_peminjaman)>
                        {{ $item->barang->nama_barang ?? 'Barang' }} - {{ $item->pengguna->nama ?? 'Pengguna' }}
                    </option>
                @endforeach
            </select>
            @error('id_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="datetime-local" name="waktu_pengajuan" value="{{ old('waktu_pengajuan', now()->format('Y-m-d\TH:i')) }}" class="form-control" required>
            @error('waktu_pengajuan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Perpanjangan Sampai</label>
            <input type="datetime-local" name="waktu_perpanjangan" value="{{ old('waktu_perpanjangan') }}" class="form-control" required>
            @error('waktu_perpanjangan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Alasan</label>
            <textarea name="alasan" rows="3" class="form-control" required>{{ old('alasan') }}</textarea>
            @error('alasan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ route('perpanjangan.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary" type="submit">Kirim Pengajuan</button>
    </div>
</form>
@endsection
