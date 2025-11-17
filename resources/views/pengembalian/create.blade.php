@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Form Pengembalian</h1>
<form method="POST" action="{{ route('pengembalian.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjaman --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}">{{ $item->barang->nama_barang ?? 'Barang' }} - {{ $item->pengguna->nama ?? 'Pengguna' }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Pengembalian</label>
            <input type="datetime-local" name="waktu_pengembalian" value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ route('pengembalian.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </div>
</form>
@endsection
