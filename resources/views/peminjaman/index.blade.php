@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $role = $user?->role;

    // Hanya dipakai petugas
    $activeList = $role === 'petugas'
        ? $peminjaman->filter(fn($p) => $p->status === 'berlangsung')
        : collect();
@endphp

@if($role === 'petugas')
    {{-- =================== VIEW PETUGAS: PEMINJAMAN AKTIF =================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Peminjaman Aktif</h1>
            <small class="text-muted">Validasi QR, lihat detail, dan pantau transaksi berjalan.</small>
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- Card Filter --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Filter Peminjaman</div>
                            <small class="text-muted">Tanggal pinjam, status, atau cari mahasiswa/barang</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label form-label-modern">Tanggal Pinjam</label>
                            <input type="date" id="filter-date" class="form-control form-control-modern" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-modern">Status</label>
                            <select id="filter-status" class="form-select form-control-modern">
                                <option value="">Semua</option>
                                <option value="berlangsung">Dipinjam</option>
                                <option value="selesai">Selesai</option>
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
        </div>

        {{-- Card Scan QR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                            🔍
                        </div>
                        <div>
                            <div class="fw-semibold">Scan QR Mahasiswa</div>
                            <small class="text-muted">Validasi sebelum serah terima</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-modern">Kode QR</label>
                        <div class="input-group">
                            <input type="text" id="scan-input" class="form-control form-control-modern" placeholder="Masukkan / scan kode QR" />
                            <button class="btn btn-modern btn-modern-primary" id="btn-scan">Proses</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern">Pilih Kamera</label>
                        <select id="camera-select" class="form-select form-control-modern">
                            <option value="" disabled selected>Sedang memuat kamera...</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label form-label-modern">Scan via Kamera</label>
                        <div id="qr-reader" class="border rounded p-2" style="display:none;"></div>
                    </div>

                    <small class="text-muted">Isi QR: ID Mahasiswa, ID Peminjaman, Kode Transaksi.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PEMINJAMAN AKTIF --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Peminjaman Aktif</h5>
                <small class="text-muted">Transaksi yang sudah divalidasi QR</small>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success">
                {{ $activeList->count() }} aktif
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>Barang</th>
                        <th>Tanggal Pinjam</th>
                        <th>Estimasi Pengembalian</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="aktif-table">
                    @forelse($activeList as $item)
                        @php
                            $badge = $item->status === 'berlangsung' ? 'info' : ($item->status === 'selesai' ? 'success' : 'secondary');
                            $statusLabel = $item->status === 'berlangsung' ? 'Dipinjam ✔️' : ucfirst($item->status);
                        @endphp
                        <tr data-aktif-row
                            data-date="{{ \Carbon\Carbon::parse($item->waktu_awal)->toDateString() }}"
                            data-status="{{ $item->status }}"
                            data-search="{{ strtolower(($item->pengguna->nama ?? '') . ' ' . ($item->barang->nama_barang ?? '')) }}"
                            data-qr="{{ $item->qr->qr_code ?? '' }}">
                            <td>
                                {{ $item->pengguna->nama ?? '-' }}<br>
                                <small class="text-muted">{{ $item->pengguna->email ?? '' }}</small>
                            </td>
                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $badge }} status-chip">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary btn-detail"
                                            data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                            data-email="{{ $item->pengguna->email ?? '-' }}"
                                            data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                            data-kode="{{ $item->barang->kode_barang ?? '-' }}"
                                            data-mulai="{{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                            data-akhir="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                                            data-status="{{ ucfirst($item->status) }}"
                                            data-qr="{{ $item->qr->qr_code ?? '-' }}"
                                            data-riwayat="Peminjaman aktif. Riwayat perpanjangan: {{ $item->perpanjangan->count() }} kali. Keluhan: {{ $item->keluhan->count() }}.">
                                        Detail
                                    </button>
                                    @if($item->qr)
                                        <button class="btn btn-sm btn-modern btn-modern-success btn-scan-attach" data-qr="{{ $item->qr->qr_code }}">
                                            🔍 Scan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada peminjaman aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL DETAIL TRANSAKSI --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
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
                                <div class="fw-semibold mb-1">Riwayat Transaksi</div>
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

    {{-- SCRIPT QR SCAN & FILTER (KHUSUS PETUGAS) --}}
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const aktifRows = document.querySelectorAll('[data-aktif-row]');
            const filterDate = document.getElementById('filter-date');
            const filterStatus = document.getElementById('filter-status');
            const filterSearch = document.getElementById('filter-search');
            const scanInput = document.getElementById('scan-input');
            const scanBtn = document.getElementById('btn-scan');
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            const cameraSelect = document.getElementById('camera-select');
            const qrReader = document.getElementById('qr-reader');
            let scanner = null;
            let isCameraActive = false;

            function showToast(message, variant = 'success') {
                const wrapper = document.createElement('div');
                wrapper.className = `alert alert-${variant} alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow`;
                wrapper.role = 'alert';
                wrapper.style.zIndex = 1080;
                wrapper.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(wrapper);
                setTimeout(() => wrapper.remove(), 3000);
            }

            function applyFilters() {
                const dateVal = filterDate.value;
                const statusVal = filterStatus.value;
                const searchVal = filterSearch.value.toLowerCase();

                [...aktifRows].forEach(row => {
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

            function startScanner(cameraId) {
                if (!window.Html5Qrcode) return;
                if (isCameraActive && scanner) {
                    scanner.stop();
                }
                qrReader.style.display = 'block';
                scanner = new Html5Qrcode('qr-reader');
                scanner.start(
                    cameraId,
                    { fps: 10, qrbox: 220 },
                    (decodedText) => {
                        scanInput.value = decodedText;
                        scanBtn.click();
                        showToast('QR terbaca: ' + decodedText, 'success');
                        scanner.stop();
                        isCameraActive = false;
                        qrReader.style.display = 'none';
                    }
                ).then(() => {
                    isCameraActive = true;
                }).catch(err => {
                    console.error('Gagal menghidupkan kamera:', err);
                    showToast('Kamera tidak bisa diakses.', 'danger');
                });
            }

            if (window.Html5Qrcode) {
                Html5Qrcode.getCameras().then(devices => {
                    cameraSelect.innerHTML = '';
                    devices.forEach((cam, i) => {
                        const opt = document.createElement('option');
                        opt.value = cam.id;
                        opt.textContent = cam.label || `Kamera ${i + 1}`;
                        cameraSelect.appendChild(opt);
                    });
                    if (devices.length > 0) {
                        startScanner(devices[0].id);
                    }
                    cameraSelect.addEventListener('change', function () {
                        if (this.value) startScanner(this.value);
                    });
                }).catch(err => {
                    console.error('Tidak bisa memuat kamera', err);
                });
            }

            function markAsScanned(qrCode) {
                if (!qrCode) return false;
                let kodeTransaksi = qrCode;
                try {
                    const parsed = JSON.parse(qrCode);
                    if (parsed?.kode_transaksi) {
                        kodeTransaksi = parsed.kode_transaksi;
                    }
                } catch (e) {
                    // plain string, keep as is
                }
                let found = false;
                document.querySelectorAll('[data-aktif-row]').forEach(row => {
                    if (row.getAttribute('data-qr') === kodeTransaksi) {
                        const chip = row.querySelector('.status-chip');
                        if (chip) {
                            chip.className = 'badge bg-success status-chip';
                            chip.textContent = 'Dipinjam ✔️';
                        }
                        found = true;
                    }
                });
                return found;
            }

            scanBtn?.addEventListener('click', () => {
                const qrCode = scanInput.value.trim();
                if (!qrCode) {
                    showToast('Isi kode QR terlebih dahulu.', 'warning');
                    return;
                }

                const ok = markAsScanned(qrCode);
                if (ok) {
                    showToast('QR valid. Status berubah menjadi Dipinjam.', 'success');
                } else {
                    showToast('QR tidak ditemukan pada daftar aktif.', 'danger');
                }
                scanInput.value = '';
            });

            document.querySelectorAll('.btn-scan-attach').forEach(btn => {
                btn.addEventListener('click', () => {
                    scanInput.value = btn.dataset.qr;
                    scanBtn.click();
                });
            });
        });
    </script>

@else
    {{-- =================== VIEW MAHASISWA: PEMINJAMAN SAYA =================== --}}

    <style>
        .peminjaman-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 0 2.5rem;
        }

        .peminjaman-card {
            border-radius: 18px;
            border: 1px solid rgba(148,163,184,0.35);
            background: rgba(255,255,255,0.98);
            box-shadow: 0 18px 45px rgba(15,23,42,0.06);
        }

        body.sipkam-dark .peminjaman-card {
            background: rgba(2,6,23,0.98);
            border-color: rgba(148,163,184,0.7);
        }

        .btn-peminjaman-primary {
            border-radius: 999px;
            background: linear-gradient(90deg,#22c55e,#14b8a6);
            border: none;
            color: #ffffff;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding-inline: 1.1rem;
            padding-block: 0.55rem;
            font-size: 0.9rem;
            box-shadow: 0 18px 35px rgba(16,185,129,0.35);
        }

        .btn-peminjaman-primary:hover {
            filter: brightness(1.05);
            color: #ffffff;
        }

        body.sipkam-dark .btn-peminjaman-primary {
            box-shadow: 0 18px 45px rgba(34,197,94,0.5);
        }

        .peminjaman-table thead th {
            border-bottom: none;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 0.70rem 0.9rem;
            background: rgba(15,23,42,0.03);
        }

        .peminjaman-table tbody td {
            padding: 0.70rem 0.9rem;
            vertical-align: middle;
        }

        .peminjaman-table th.col-kode,
        .peminjaman-table td.col-kode {
            width: 80px;
            white-space: nowrap;
        }

        .peminjaman-table td small {
            font-size: 0.78rem;
        }

        body.sipkam-dark .peminjaman-table thead th {
            background: #020617;
            color: #a7f3d0;
            border-bottom: 1px solid rgba(31,41,55,0.9);
        }

        body.sipkam-dark .peminjaman-table tbody td {
            color: #e5e7eb;
        }

        body.sipkam-dark .peminjaman-table tbody td small {
            color: #9ca3af;
        }

        body.sipkam-dark .peminjaman-table .badge {
            box-shadow: 0 0 12px rgba(34,197,94,0.45);
        }

        @media (max-width: 767.98px) {
            .peminjaman-page {
                margin: -16px -16px -24px -16px;
                padding: 16px 16px 24px;
            }
        }

        .peminjaman-card .peminjaman-table tbody td,
        .peminjaman-card .peminjaman-table tbody td small {
            color: #000000 !important;
            font-weight: 500;
        }
    </style>

    <div class="peminjaman-page">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="peminjaman-header-title">
                <h1 class="h3 mb-1 fw-semibold">Daftar Peminjaman</h1>
                <small>Pantau semua permintaan peminjaman Anda</small>
            </div>

            <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-peminjaman-primary">
                <i class="fas fa-plus"></i>
                <span>Tambah Peminjaman</span>
            </a>
        </div>

        {{-- CARD TABEL --}}
        <div class="peminjaman-card">
            <div class="table-responsive">
                <table class="table peminjaman-table align-middle">
                    <thead>
                        <tr>
                            <th class="col-kode text-nowrap">Kode</th>
                            <th>Barang</th>
                            <th class="text-nowrap">Tanggal Pinjam</th>
                            <th class="text-nowrap">Estimasi Pengembalian</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $item)
                            @php
                                $status = $item->status;
                                $badge = $status === 'berlangsung'
                                    ? 'info'
                                    : ($status === 'selesai' ? 'success' : 'danger');
                            @endphp
                            <tr>
                                <td class="col-kode">#{{ $item->id_peminjaman }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_awal)->format('d M Y H:i') }}
                                </td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('mahasiswa.peminjaman.show', $item->id_peminjaman) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endif
@endsection
