@extends('layouts.app')

@section('content')
@php
    $payload = $payload ?? null;
    $deadline = \Carbon\Carbon::parse($peminjaman->waktu_akhir);
    $now = now();
    $terlambatMenit = max(0, $now->diffInMinutes($deadline, false) * -1);
@endphp

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                        🔍
                    </div>
                    <div>
                        <div class="fw-semibold">QR Terbaca</div>
                        <small class="text-muted">Payload transaksi</small>
                    </div>
                </div>
                <div class="small text-muted mb-3">
                    <div>Kode transaksi: <strong>{{ $qr_code }}</strong></div>
                    <div>ID Peminjaman: <strong>{{ $peminjaman->id_peminjaman }}</strong></div>
                    <div>ID Mahasiswa: <strong>{{ $peminjaman->pengguna->id_pengguna ?? '-' }}</strong></div>
                    <div>ID Barang: <strong>{{ $peminjaman->barang->id_barang ?? '-' }}</strong></div>
                </div>
                <div class="p-3 rounded bg-light">
                    <div class="fw-semibold mb-1">Ringkasan</div>
                    <div class="small text-muted">Mahasiswa</div>
                    <div class="fw-semibold">{{ $peminjaman->pengguna->nama ?? '-' }}</div>
                    <div class="small text-muted mt-2">Barang</div>
                    <div class="fw-semibold">{{ $peminjaman->barang->nama_barang ?? '-' }}</div>
                    <div class="small text-muted mt-2">Periode</div>
                    <div class="fw-semibold">
                        {{ \Carbon\Carbon::parse($peminjaman->waktu_awal)->translatedFormat('d M Y H:i') }}
                        — {{ \Carbon\Carbon::parse($peminjaman->waktu_akhir)->translatedFormat('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                        📦
                    </div>
                    <div>
                        <h1 class="h5 mb-0">Form Pengembalian Barang</h1>
                        <small class="text-muted">Tentukan kondisi, denda otomatis, dan dokumentasi</small>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('petugas.pengembalian.prosesLengkap', $peminjaman->id_peminjaman) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-modern">Kondisi Barang</label>
                            <select name="kondisi" id="kondisi" class="form-select form-control-modern" required>
                                <option value="baik">🟢 Baik</option>
                                <option value="rusak">🟡 Rusak / Service</option>
                                <option value="hilang">🔴 Hilang</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label form-label-modern">Waktu Pengembalian</label>
                            <input
                                type="datetime-local"
                                name="waktu_pengembalian"
                                id="waktu-pengembalian"
                                class="form-control form-control-modern"
                                data-deadline="{{ $deadline->format('Y-m-d\TH:i') }}"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: textfield;"
                            />
                            <small class="text-muted" id="lateness-info">
                                Terlambat: {{ $terlambatMenit }} menit (Rp {{ number_format($terlambatMenit * 1000, 0, ',', '.') }})
                            </small>
                        </div>

                        <div class="col-md-6" id="field-biaya-rusak" style="display:none;">
                            <label class="form-label form-label-modern">Biaya Denda Kerusakan</label>
                            <input type="number" name="biaya_rusak" id="biaya_rusak" min="0" class="form-control form-control-modern" placeholder="Contoh: 50000">
                            <small class="text-muted">Auto-saran: Rp <span id="suggest-rusak">0</span></small>
                        </div>

                        <div class="col-md-6" id="field-biaya-hilang" style="display:none;">
                            <label class="form-label form-label-modern">Denda Kehilangan</label>
                            <input type="number" name="biaya_hilang" id="biaya_hilang" min="0" class="form-control form-control-modern" placeholder="Contoh: 250000">
                        </div>

                        <div class="col-12" id="field-rincian-rusak" style="display:none;">
                            <label class="form-label form-label-modern">Rincian Kerusakan</label>
                            <textarea name="catatan" rows="3" class="form-control form-control-modern" placeholder="Deskripsikan kerusakan"></textarea>
                        </div>

                        <div class="col-12" id="field-foto-rusak" style="display:none;">
                            <label class="form-label form-label-modern">Upload Foto Kerusakan (opsional)</label>
                            <input type="file" name="foto_kerusakan" accept="image/*" class="form-control form-control-modern">
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                        <button type="submit" class="btn btn-modern btn-modern-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ========================== --}}
{{--  FIX DATE INPUT DEFAULT    --}}
{{-- ========================== --}}
<style>
    #waktu-pengembalian::-webkit-calendar-picker-indicator,
    #waktu-pengembalian::-webkit-inner-spin-button {
        display: none;
    }
    #waktu-pengembalian {
        caret-color: transparent;
    }
</style>

{{-- ========================== --}}
{{--  CSS FIX — AGAR TIDAK HIJAU --}}
{{-- ========================== --}}
<style>
    select,
    .form-select,
    .form-control,
    .form-control-modern {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }

    select option,
    .form-select option {
        background-color: #ffffff !important;
        color: #0f172a !important;
    }

    select option:hover,
    .form-select option:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }

    select option:checked,
    .form-select option:checked {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kondisi = document.getElementById('kondisi');
        const fieldRusak = document.getElementById('field-rincian-rusak');
        const fieldFoto = document.getElementById('field-foto-rusak');
        const fieldBiayaRusak = document.getElementById('field-biaya-rusak');
        const fieldBiayaHilang = document.getElementById('field-biaya-hilang');
        const suggestRusak = document.getElementById('suggest-rusak');
        const biayaHilang = document.getElementById('biaya_hilang');
        const waktuInput = document.getElementById('waktu-pengembalian');
        const latenessInfo = document.getElementById('lateness-info');
        const hargaBarang = Number('{{ $peminjaman->barang->harga ?? 0 }}');

        const kategori = '{{ strtolower($peminjaman->barang->kategori->nama ?? '') }}';
        let saran = 50000;
        if (kategori.includes('elektronik')) saran = 150000;
        if (kategori.includes('laboratorium')) saran = 200000;
        suggestRusak.textContent = saran.toLocaleString('id-ID');

        function formatDateTimeLocal(date) {
            const pad = (n) => String(n).padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }

        function updateLateness() {
            if (!waktuInput || !latenessInfo) return;
            const deadlineStr = waktuInput.dataset.deadline;
            const deadline = deadlineStr ? new Date(deadlineStr.replace(' ', 'T')) : null;

            const now = new Date();
            waktuInput.value = formatDateTimeLocal(now);

            if (!deadline) return;

            const diffMs = now.getTime() - deadline.getTime();
            const diffMinutes = Math.max(0, Math.ceil(diffMs / 60000));
            const denda = diffMinutes * 1000;
            latenessInfo.textContent = `Terlambat: ${diffMinutes} menit (Rp ${denda.toLocaleString('id-ID')})`;
        }

        function toggleFields() {
            const val = kondisi.value;
            const isRusak = val === 'rusak';
            const isHilang = val === 'hilang';

            fieldRusak.style.display = isRusak ? '' : 'none';
            fieldFoto.style.display = isRusak ? '' : 'none';
            fieldBiayaRusak.style.display = isRusak ? '' : 'none';
            fieldBiayaHilang.style.display = isHilang ? '' : 'none';

            if (isHilang && biayaHilang) {
                biayaHilang.value = hargaBarang || 0;
                biayaHilang.readOnly = true;
            } else if (biayaHilang) {
                biayaHilang.readOnly = false;
                biayaHilang.value = '';
            }
        }

        kondisi.addEventListener('change', toggleFields);
        toggleFields();

        updateLateness();
        setInterval(updateLateness, 5000);

        if (waktuInput) {
            waktuInput.addEventListener('input', updateLateness);
        }

        const form = document.querySelector('form[action*="pengembalian"][method="POST"]');
        if (form) {
            form.addEventListener('submit', function() {
                updateLateness();
            });
        }
    });
</script>

@endsection
