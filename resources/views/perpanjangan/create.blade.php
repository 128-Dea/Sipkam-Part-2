@extends('layouts.app')

@section('content')
<style>
    .btn-perpanjangan-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 12px 30px rgba(79,70,229,0.25);
    }

    .btn-perpanjangan-primary:hover {
        color: #ffffff;
        filter: brightness(1.05);
    }

    body.sipkam-dark .btn-perpanjangan-primary {
        box-shadow: 0 12px 32px rgba(99,102,241,0.35);
    }
</style>

<h1 class="h3 mb-4">Ajukan Perpanjangan</h1>

<form method="POST" action="{{ route('mahasiswa.perpanjangan.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" id="select-peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjaman --</option>
                @foreach($peminjaman as $item)
                    <option
                        value="{{ $item->id_peminjaman }}"
                        data-end="{{ \Carbon\Carbon::parse($item->waktu_akhir)->format('Y-m-d\TH:i') }}"
                        data-end-display="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                        @selected(old('id_peminjaman')==$item->id_peminjaman)
                    >
                        {{ $item->barang->nama_barang ?? 'Barang' }} - {{ $item->pengguna->nama ?? 'Pengguna' }}
                    </option>
                @endforeach
            </select>
            @error('id_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
            <small id="current-deadline" class="text-muted d-block mt-1"></small>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="datetime-local" name="waktu_pengajuan" value="{{ old('waktu_pengajuan', now()->format('Y-m-d\TH:i')) }}" class="form-control" required>
            @error('waktu_pengajuan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Perpanjangan Sampai</label>
            <input type="datetime-local" name="waktu_perpanjangan" id="waktu-perpanjangan" value="{{ old('waktu_perpanjangan') }}" class="form-control" required>
            @error('waktu_perpanjangan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Alasan</label>
            <textarea name="alasan" rows="3" class="form-control" required>{{ old('alasan') }}</textarea>
            @error('alasan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ route('mahasiswa.perpanjangan.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-perpanjangan-primary" type="submit">Kirim Pengajuan</button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('select-peminjaman');
    const endInfo = document.getElementById('current-deadline');
    const endInput = document.getElementById('waktu-perpanjangan');

    const applyDeadline = () => {
        const option = select.options[select.selectedIndex];
        const endValue = option ? option.dataset.end : null;
        const endDisplay = option ? option.dataset.endDisplay : '';

        if (endInfo) {
            endInfo.textContent = endDisplay ? `Batas saat ini: ${endDisplay}` : '';
        }

        if (endValue && endInput) {
            endInput.min = endValue;
            // Jika belum ada nilai di input, set default 10 menit setelah batas sekarang
            if (!endInput.value) {
                const base = new Date(endValue);
                base.setMinutes(base.getMinutes() + 10);
                const iso = base.toISOString().slice(0,16);
                endInput.value = iso;
            }
        }
    };

    if (select) {
        select.addEventListener('change', applyDeadline);
        applyDeadline();
    }
});
</script>
@endpush
@endsection
