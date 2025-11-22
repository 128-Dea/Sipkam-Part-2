@extends('layouts.app')

@section('content')
@php
    $bookingList = $booking->filter(fn($p) => $p->status !== 'selesai');
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Booking Barang</h1>
        <small class="text-muted">Filter berdasarkan tanggal/status, lihat detail, dan pastikan jadwal tidak bentrok.</small>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
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
</div>

<div class="card border-0 shadow-sm">
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
                        $statusLabel = $bookingStatus === 'ditolak' ? 'Ditolak (Bentrok) 🕓' : 'Disetujui ✔️';
                        $badge = $bookingStatus === 'ditolak' ? 'danger' : 'success';
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
                            <button class="btn btn-sm btn-outline-primary btn-detail"
                                    data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                    data-email="{{ $item->pengguna->email ?? '-' }}"
                                    data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                    data-kode="{{ $item->barang->kode_barang ?? '-' }}"
                                    data-mulai="{{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                    data-akhir="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                                    data-status="{{ ucfirst($item->status) }}"
                                    data-qr="{{ $item->qr->qr_code ?? '-' }}"
                                    data-riwayat="Booking dibuat dan menunggu scan QR.">
                                Lihat Detail
                            </button>
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

{{-- Modal Detail --}}
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
                                <div id="detail-qr" class="text-monospace"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded border bg-white shadow-sm">
                            <div class="fw-semibold mb-1">Riwayat</div>
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
            const dateVal = filterDate.value;
            const statusVal = filterStatus.value;
            const searchVal = filterSearch.value.toLowerCase();

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
