@extends('layouts.app')

@section('content')
@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;

    $bookingList = $booking->filter(fn($p) => $p->status !== 'selesai');
@endphp

{{-- ===================== STYLE TEMA GRADASI HIJAU ===================== --}}
<style>
    :root {
        --book-dark-1: #051F20;
        --book-dark-2: #0B2B26;
        --book-dark-3: #163832;
        --book-mid:    #253547;
        --book-soft:   #8EB69B;
        --book-light:  #DAF1DE;
    }

    /* WRAPPER HALAMAN BOOKING */
    .sipkam-booking-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .sipkam-booking-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media (max-width: 767.98px) {
        .sipkam-booking-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .sipkam-booking-inner {
            max-width: 100%;
        }
    }

    /* HEADER ATAS */
    .sipkam-booking-header {
        background: rgba(5, 31, 32, 0.96);
        border-radius: 20px 20px 0 0;
        padding: 20px 24px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        justify-content: space-between;
    }

    .sipkam-booking-header h1.h3 {
        color: var(--book-light);
        font-weight: 650;
        letter-spacing: .03em;
        margin-bottom: 4px;
    }

    .sipkam-booking-header small.text-muted {
        color: rgba(218, 241, 222, 0.9) !important;
    }

    @media (max-width: 767.98px) {
        .sipkam-booking-header {
            padding: 18px 16px;
            border-radius: 16px 16px 0 0;
        }
    }

    /* CARD FILTER DI BAWAH HEADER */
    .sipkam-booking-filter-card {
        border-radius: 0 0 18px 18px;
        margin-top: -1px; /* nempel ke header */
        border: none;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.35);
    }

    .sipkam-booking-filter-card .card-body {
        padding: 18px 22px 20px;
    }

    .sipkam-booking-filter-card .form-label-modern {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #475569;
        margin-bottom: 6px;
    }

    .sipkam-booking-filter-card .form-control-modern,
    .sipkam-booking-filter-card .form-select.form-control-modern {
        border-radius: 14px;
        border-color: #e2ece5;
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.15);
        font-size: 0.9rem;
        padding: 10px 14px;
        background-color: #ffffff;
    }

    .sipkam-booking-filter-card .form-control-modern::placeholder {
        color: #94a3b8;
    }

    .sipkam-booking-filter-card .form-control-modern:focus,
    .sipkam-booking-filter-card .form-select.form-control-modern:focus {
        border-color: var(--book-soft);
        box-shadow:
            0 0 0 1px rgba(142, 182, 155, 0.45),
            0 0 0 4px rgba(142, 182, 155, 0.18);
    }

    /* CARD TABEL BOOKING */
    .sipkam-booking-table-card {
        margin-top: 20px;
        border-radius: 18px;
        border: none;
        background: rgba(250, 253, 252, 0.97);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
        overflow: hidden;
    }

    .sipkam-booking-table-card .card-header {
        background: linear-gradient(135deg, var(--book-dark-2), var(--book-dark-3));
        color: #e9f7f0;
        border: none;
        padding: 16px 22px;
    }

    .sipkam-booking-table-card .card-header h5 {
        color: #ffffff;
        font-weight: 600;
    }

    .sipkam-booking-table-card .card-header small.text-muted {
        color: rgba(218, 241, 222, 0.85) !important;
    }

    .sipkam-booking-table-card .badge.bg-primary.bg-opacity-10.text-primary {
        background: rgba(218, 241, 222, 0.06) !important;
        color: var(--book-light) !important;
        border-radius: 999px;
        border: 1px solid rgba(142, 182, 155, 0.65);
        font-weight: 500;
        padding-inline: 12px;
    }

    .sipkam-booking-table-card .table {
        margin-bottom: 0;
    }

    .sipkam-booking-table-card thead.table-light {
        background-color: var(--book-dark-2) !important;
    }

    .sipkam-booking-table-card thead.table-light th {
        background-color: transparent !important;
        color: #e5f9ee;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        border-color: rgba(148, 163, 184, 0.35);
    }

    .sipkam-booking-table-card tbody td {
        font-size: 0.92rem;
        color: var(--book-dark-3);
        border-color: #e1ebe4;
        vertical-align: middle;
    }

    .sipkam-booking-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .sipkam-booking-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .sipkam-booking-table-card .text-muted {
        color: rgba(71, 85, 105, 0.8) !important;
    }

    /* BADGE STATUS */
    .sipkam-booking-table-card .badge.bg-success {
        background: linear-gradient(135deg, #16a34a, var(--book-soft)) !important;
        border-radius: 999px;
        padding-inline: 0.9rem;
        font-weight: 600;
    }

    .sipkam-booking-table-card .badge.bg-danger {
        border-radius: 999px;
        font-weight: 600;
    }

    /* TOMBOL DETAIL / BATAL sedikit membulat */
    .sipkam-booking-table-card .btn.btn-sm {
        border-radius: 999px;
        padding-inline: 0.9rem;
    }
</style>

<div class="sipkam-booking-wrapper">
    <div class="sipkam-booking-inner">

        {{-- HEADER ATAS (tidak mengubah logika, hanya tambah class) --}}
        <div class="sipkam-booking-header d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Booking Barang</h1>
                <small class="text-muted">
                    Filter berdasarkan tanggal/status, lihat detail, dan pastikan jadwal tidak bentrok.
                </small>
            </div>
        </div>

        {{-- CARD FILTER (logika tetap, hanya tambah class) --}}
        <div class="card border-0 shadow-sm mb-3 sipkam-booking-filter-card">
            @if(auth()->user()?->role === 'petugas')
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Filter Booking</div>
                            <small class="text-muted">Tanggal booking, status, atau cari mahasiswa/barang</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label form-label-modern">Tanggal Booking</label>
                            <input type="date" id="filter-date" class="form-control form-control-modern" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-modern">Status Booking</label>
                            <select id="filter-status" class="form-select form-control-modern">
                                <option value="">Semua</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-modern">Cari Mahasiswa / Barang</label>
                            <input type="text" id="filter-search" class="form-control form-control-modern" placeholder="Ketik nama atau barang" />
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- CARD TABEL BOOKING (logika sama, hanya tambah class) --}}
        <div class="card border-0 shadow-sm sipkam-booking-table-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Daftar Booking</h5>
                    <small class="text-muted">Ditolak jika bentrok, disetujui jika kosong. Scan QR lanjutkan ke peminjaman.</small>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                    {{ $bookingList->count() }} booking
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Booking</th>
                            <th>Nama Mahasiswa</th>
                            <th>Barang</th>
                            <th>Status Booking</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="booking-table">
                        @forelse($bookingList as $item)
                            @php
                                $bookingStatus = $item->status === 'ditolak' ? 'ditolak' : 'disetujui';
                                $statusLabel = $bookingStatus === 'ditolak' ? 'Ditolak (Bentrok)' : 'Disetujui';
                                $badge = $bookingStatus === 'ditolak' ? 'danger' : 'success';
                                $qrPayload = $item->qr?->payload;
                                $qrSvg = $qrPayload ? base64_encode(QrCode::format('svg')->size(180)->margin(1)->generate($qrPayload)) : null;
                            @endphp
                            <tr data-booking-row
                                data-date="{{ \Carbon\Carbon::parse($item->waktu_awal)->toDateString() }}"
                                data-status="{{ $bookingStatus }}"
                                data-search="{{ strtolower(($item->pengguna->nama ?? '') . ' ' . ($item->barang->nama_barang ?? '')) }}">
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($item->waktu_awal)->format('H:i') }}</small>
                                </td>
                                <td>
                                    {{ $item->pengguna->nama ?? '-' }}<br>
                                    <small class="text-muted">{{ $item->pengguna->email ?? '' }}</small>
                                </td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $badge }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        <button class="btn btn-sm btn-outline-primary btn-detail"
                                                data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                                data-email="{{ $item->pengguna->email ?? '-' }}"
                                                data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                                data-kode="{{ $item->barang->kode_barang ?? '-' }}"
                                                data-mulai="{{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                                data-akhir="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                                                data-status="{{ ucfirst($item->status) }}"
                                                data-qr="{{ $item->qr->qr_code ?? '-' }}"
                                                data-qr-svg="{{ $qrSvg }}"
                                                data-riwayat="Booking dibuat dan menunggu scan QR.">
                                            Lihat Detail
                                        </button>
                                        @if(auth()->user()?->role === 'mahasiswa' && $item->status === 'booking')
                                            <form method="POST"
                                                  action="{{ route('mahasiswa.peminjaman.cancel', $item->id_peminjaman) }}"
                                                  onsubmit="return confirm('Batalkan booking ini? QR akan dinonaktifkan.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Batalkan Booking
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada booking.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ============ MODAL & SCRIPT TETAP SAMA, TIDAK DIUBAH LOGIKANYA ============ --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded border bg-light">
                            <div class="fw-semibold mb-1">Mahasiswa</div>
                            <div id="detail-nama"></div>
                            <small class="text-muted" id="detail-email"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded border bg-light">
                            <div class="fw-semibold mb-1">Barang</div>
                            <div id="detail-barang"></div>
                            <small class="text-muted" id="detail-kode"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded border bg-white shadow-sm h-100">
                            <div class="fw-semibold mb-2">Periode</div>
                            <div class="text-muted small mb-1">Mulai</div>
                            <div id="detail-mulai" class="fw-semibold"></div>
                            <div class="text-muted small mt-2 mb-1">Estimasi Kembali</div>
                            <div id="detail-akhir" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded border bg-white shadow-sm h-100">
                            <div class="fw-semibold mb-2">Status</div>
                            <span class="badge bg-primary" id="detail-status"></span>
                            <div class="mt-3">
                                <div class="fw-semibold mb-1">QR / Kode Transaksi</div>
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                                    <div id="detail-qr-code" class="bg-light rounded p-2 d-inline-flex justify-content-center align-items-center" style="min-width:160px;min-height:160px;"></div>
                                    <div>
                                        <div id="detail-qr" class="text-monospace small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded border bg-white shadow-sm">
                            <div class="fw-semibold mb-1">riwayat</div>
                            <p class="mb-0 text-muted" id="detail-riwayat"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookingRows = document.querySelectorAll('[data-booking-row]');
        const filterDate = document.getElementById('filter-date');
        const filterStatus = document.getElementById('filter-status');
        const filterSearch = document.getElementById('filter-search');
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

        function applyFilters() {
            const dateVal = filterDate ? filterDate.value : '';
            const statusVal = filterStatus ? filterStatus.value : '';
            const searchVal = filterSearch ? filterSearch.value.toLowerCase() : '';

            bookingRows.forEach(row => {
                const rowDate = row.getAttribute('data-date');
                const rowStatus = row.getAttribute('data-status');
                const rowSearch = row.getAttribute('data-search') ?? '';

                const matchDate = !dateVal || rowDate === dateVal;
                const matchStatus = !statusVal || rowStatus === statusVal;
                const matchSearch = !searchVal || rowSearch.includes(searchVal);

                row.style.display = (matchDate && matchStatus && matchSearch) ? '' : 'none';
            });
        }

        [filterDate, filterStatus, filterSearch].forEach(el => {
            if (el) el.addEventListener('input', applyFilters);
        });

        function fillModal(btn) {
            document.getElementById('detail-nama').textContent = btn.dataset.nama;
            document.getElementById('detail-email').textContent = btn.dataset.email;
            document.getElementById('detail-barang').textContent = btn.dataset.barang;
            document.getElementById('detail-kode').textContent = btn.dataset.kode;
            document.getElementById('detail-mulai').textContent = btn.dataset.mulai;
            document.getElementById('detail-akhir').textContent = btn.dataset.akhir;
            document.getElementById('detail-status').textContent = btn.dataset.status;
            document.getElementById('detail-qr').textContent = btn.dataset.qr;
            document.getElementById('detail-riwayat').textContent = btn.dataset.riwayat;

            const qrContainer = document.getElementById('detail-qr-code');
            qrContainer.innerHTML = '';
            const svgEncoded = btn.dataset.qrSvg;
            if (svgEncoded) {
                const svgMarkup = atob(svgEncoded);
                qrContainer.innerHTML = svgMarkup;
            } else {
                qrContainer.innerHTML = '<span class="text-muted small">QR tidak tersedia</span>';
            }
        }

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                fillModal(btn);
                detailModal.show();
            });
        });
    });
</script>
@endsection
