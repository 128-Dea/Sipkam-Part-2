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

{{-- =========================
   TEMA GRADASI HIJAU (UMUM)
   ========================== --}}
<style>
    :root {
        --sipkam-deep-1: #051F20;
        --sipkam-deep-2: #0B2B26;
        --sipkam-deep-3: #163832;
        --sipkam-deep-4: #235347;
        --sipkam-soft:  #8EB69B;
        --sipkam-mist:  #DAF1DE;
        --sipkam-neon:  #a7f3d0;
    }

    /* ====== WRAPPER PETUGAS (GRADIENT) ====== */
    .peminjaman-petugas-bg {
        margin: -24px -32px -24px -32px;              /* nempel ke top bar */
        padding: 24px 32px 40px;
        min-height: calc(100vh - 64px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .peminjaman-petugas-inner {
        width: 100%;
        max-width: 1180px;
    }

    @media (max-width: 991.98px) {
        .peminjaman-petugas-bg {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .peminjaman-petugas-inner {
            max-width: 100%;
        }
    }

    /* ====== HEADER PETUGAS ====== */
    .peminjaman-petugas-hero {
        margin-bottom: 1.5rem;
        border-radius: 24px;
        padding: 1.8rem 2rem;
        background: linear-gradient(135deg, #051F20, #0F3533);
        box-shadow: 0 20px 45px rgba(3, 26, 23, 0.35);
    }

    .peminjaman-petugas-hero .text-muted {
        color: rgba(233, 247, 240, 0.8) !important;
    }

    .peminjaman-petugas-hero .peminjaman-petugas-header {
        margin-bottom: 0;
    }

    .peminjaman-petugas-header h1.h3 {
        color: #e9f7f0;
        font-weight: 650;
        letter-spacing: 0.03em;
        text-shadow: 0 0 20px rgba(0,0,0,0.65);
    }

    .peminjaman-petugas-header small.text-muted {
        color: rgba(218,241,222,0.9) !important;
    }

    /* ====== CARD FILTER & QR ====== */
    .peminjaman-filter-card,
    .peminjaman-qr-card {
        border-radius: 20px;
        border: 1px solid rgba(218,241,222,0.85);
        background: rgba(250,253,252,0.98);
        box-shadow: 0 20px 40px rgba(3, 26, 23, 0.40);
    }

    .peminjaman-filter-card .form-label-modern,
    .peminjaman-qr-card .form-label-modern {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #475569;
        margin-bottom: 6px;
    }

    .peminjaman-filter-card .form-control-modern,
    .peminjaman-filter-card .form-select.form-control-modern,
    .peminjaman-qr-card .form-control-modern,
    .peminjaman-qr-card .form-select.form-control-modern {
        border-radius: 999px;
        border: 1px solid rgba(148,163,184,0.45);
        background: rgba(255,255,255,0.98);
        box-shadow:
            0 8px 22px rgba(15,23,42,0.08),
            inset 0 0 0 1px rgba(148,163,184,0.12);
        font-size: 0.9rem;
        padding: 0.55rem 0.95rem;
    }

    .peminjaman-filter-card .form-control-modern:focus,
    .peminjaman-filter-card .form-select.form-control-modern:focus,
    .peminjaman-qr-card .form-control-modern:focus,
    .peminjaman-qr-card .form-select.form-control-modern:focus {
        border-color: var(--sipkam-soft);
        box-shadow:
            0 0 0 1px rgba(142,182,155,0.5),
            0 0 0 4px rgba(142,182,155,0.2);
    }

    .peminjaman-filter-card .rounded-circle,
    .peminjaman-qr-card .rounded-circle {
        background: radial-gradient(circle at 30% 20%, var(--sipkam-soft), var(--sipkam-deep-3));
        color: #e9f7f0;
    }

    /* tombol proses QR */
    .peminjaman-qr-card .btn-modern-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--sipkam-deep-2), var(--sipkam-deep-4));
        box-shadow: 0 16px 32px rgba(3,26,23,0.55);
    }

    .peminjaman-qr-card .btn-modern-primary:hover {
        filter: brightness(1.05);
    }

    /* ====== CARD TABEL AKTIF ====== */
    .peminjaman-table-card {
        margin-top: 18px;
        border-radius: 20px;
        border: 1px solid rgba(218,241,222,0.9);
        background: rgba(250,253,252,0.98);
        box-shadow: 0 24px 50px rgba(3, 26, 23, 0.45);
        overflow: hidden;
    }

    .peminjaman-table-card .card-header {
        background: linear-gradient(135deg, var(--sipkam-deep-1), var(--sipkam-deep-3));
        color: #e9f7f0;
        border-bottom: 1px solid rgba(15,23,42,0.7);
        padding-block: 0.9rem;
    }

    .peminjaman-table-card .card-header h5 {
        color: var(--sipkam-mist);
        font-weight: 600;
    }

    .peminjaman-table-card .card-header small {
        color: rgba(218,241,222,0.9) !important;
    }

    .peminjaman-table-card .badge.bg-success.bg-opacity-10 {
        background: rgba(218,241,222,0.12) !important;
        border: 1px solid rgba(142,182,155,0.85);
        color: var(--sipkam-mist);
        border-radius: 999px;
        font-weight: 500;
    }

    .peminjaman-table-card thead.table-light {
        background: var(--sipkam-deep-2) !important;
    }

    .peminjaman-table-card thead.table-light th {
        background: transparent !important;
        color: #e5f9ee;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        border-bottom: none;
    }

    .peminjaman-table-card tbody td {
        border-color: rgba(203,213,225,0.7);
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .peminjaman-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .peminjaman-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .peminjaman-table-card .status-chip {
        border-radius: 999px;
        padding-inline: 0.75rem;
        font-weight: 600;
    }

    /* END PETUGAS STYLE */
</style>

@if($role === 'petugas')
    {{-- =================== VIEW PETUGAS: PEMINJAMAN AKTIF =================== --}}
    <div class="peminjaman-petugas-bg">
        <div class="peminjaman-petugas-inner">

            <div class="peminjaman-petugas-hero">
                <div class="d-flex justify-content-between align-items-center peminjaman-petugas-header">
                    <div>
                        <h1 class="h3 mb-1">Peminjaman Aktif</h1>
                        <small class="text-muted">Validasi QR, lihat detail, dan pantau transaksi berjalan.</small>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                {{-- Card Filter --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100 peminjaman-filter-card">
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
                    <div class="card border-0 shadow-sm h-100 peminjaman-qr-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                                    🔍
                                </div>
                                <div>
                                    <div class="fw-semibold">Scan QR Mahasiswa</div>
                                    <small class="text-muted">Validasi sebelum aktivasi peminjaman</small>
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
            <div class="card border-0 shadow-sm peminjaman-table-card">
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
                                    data-qr="{{ $item->qr->qr_code ?? '' }}"
                                    data-qrpayload="{{ $item->qr->payload ?? '' }}">
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
                                            <button class="btn btn-sm btn-outline-success btn-detail"
                                                    data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                                    data-email="{{ $item->pengguna->email ?? '-' }}"
                                                    data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                                    data-kode="{{ $item->barang->kode_barang ?? '-' }}"
                                                    data-mulai="{{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                                    data-akhir="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                                                    data-status="{{ ucfirst($item->status) }}"
                                                    data-qr="{{ $item->qr->qr_code ?? '-' }}"
                                                    data-qrpayload="{{ $item->qr->payload ?? '' }}"
                                                    data-riwayat="Peminjaman aktif. riwayat perpanjangan: {{ $item->perpanjangan->count() }} kali. Keluhan: {{ $item->keluhan->count() }}.">
                                                Detail
                                            </button>
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

            {{-- MODAL DETAIL TRANSAKSI (LOGIKA TIDAK DIUBAH) --}}
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
                                            <div class="mt-2 text-center">
                                                <img id="detail-qr-img" src="" alt="QR Peminjaman" class="img-fluid rounded border" style="max-width: 180px; display:none;">
                                                <div id="detail-qr-empty" class="text-muted small" style="display:none;">QR belum tersedia.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded border bg-white shadow-sm">
                                        <div class="fw-semibold mb-1">riwayat Transaksi</div>
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

        </div> {{-- .peminjaman-petugas-inner --}}
    </div>     {{-- .peminjaman-petugas-bg --}}

    {{-- SCRIPT QR SCAN & FILTER (TIDAK DIUBAH) --}}
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
            let isSubmitting = false;

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
                const img = document.getElementById('detail-qr-img');
                const empty = document.getElementById('detail-qr-empty');
                const payload = btn.dataset.qrpayload || '';
                if (payload) {
                    img.src = `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(payload)}`;
                    img.style.display = 'block';
                    empty.style.display = 'none';
                } else {
                    img.style.display = 'none';
                    empty.style.display = 'block';
                }
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
                    // plain string
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

            async function activateViaApi(qrCode) {
                if (isSubmitting) return;
                isSubmitting = true;
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                try {
                    const res = await fetch('{{ route('petugas.peminjaman.activate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ qr_code: qrCode }),
                    });

                    if (!res.ok) {
                        const data = await res.json().catch(() => ({}));
                        throw new Error(data.message || 'QR tidak valid atau peminjaman tidak ditemukan');
                    }

                    showToast('Peminjaman diaktifkan. Memuat ulang...', 'success');
                    markAsScanned(qrCode);
                    setTimeout(() => window.location.reload(), 600);
                } catch (err) {
                    console.error(err);
                    showToast(err.message, 'danger');
                } finally {
                    isSubmitting = false;
                }
            }

            scanBtn?.addEventListener('click', () => {
                const qrCode = scanInput.value.trim();
                if (!qrCode) {
                    showToast('Isi kode QR terlebih dahulu.', 'warning');
                    return;
                }

                activateViaApi(qrCode);
                scanInput.value = '';
            });

        });
    </script>

@else
    {{-- =================== VIEW MAHASISWA: PEMINJAMAN SAYA =================== --}}

    <style>
        /* layout card seperti semula */
        .peminjaman-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 0 2.5rem;
        }

        .peminjaman-header-banner {
            border-radius: 24px;
            padding: 1.8rem 2rem;
            background: linear-gradient(135deg, #051F20, #0F3533);
            color: #f4f9f2;
            box-shadow: 0 20px 45px rgba(3, 26, 23, 0.35);
        }

        .peminjaman-header-banner h1 {
            margin-bottom: 0.35rem;
        }

        .peminjaman-header-banner small {
            color: rgba(244, 249, 242, 0.85);
        }

        .btn-peminjaman-primary {
            border-radius: 999px;
            background: linear-gradient(135deg, var(--sipkam-soft), var(--sipkam-mist));
            border: none;
            color: var(--sipkam-deep-1);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding-inline: 1.1rem;
            padding-block: 0.55rem;
            font-size: 0.9rem;
            box-shadow: 0 18px 35px rgba(5,31,32,0.45);
        }

        .btn-peminjaman-primary:hover {
            filter: brightness(1.05);
            color: #ffffff;
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
            background: linear-gradient(135deg, #0B2B26 0%, #8EB69B 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding-inline: 1.1rem;
            padding-block: 0.55rem;
            font-size: 0.9rem;
            box-shadow: 0 18px 35px rgba(5,31,32,0.45);
        }

        .btn-peminjaman-primary:hover {
            filter: brightness(1.05);
            color: #ffffff;
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

        /* ===========================
           GRADASI BACKGROUND SAJA
           =========================== */
        body.bg-peminjaman-mahasiswa {
            background-color: #ffffff;
            background-image: none;
            min-height: 100vh;
        }

        @media (max-width: 767.98px) {
            .peminjaman-page {
                margin: -16px -16px -24px -16px;
                padding: 16px 16px 24px;
            }
        }
    </style>

    <div class="peminjaman-page">
        {{-- HEADER --}}
        <div class="peminjaman-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h3 mb-1 fw-semibold">Daftar Peminjaman</h1>
                    <small>Pantau semua permintaan peminjaman Anda</small>
                </div>
                <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-peminjaman-primary">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Peminjaman</span>
                </a>
            </div>
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
                                $badge = match ($status) {
                                    'berlangsung' => 'info',
                                    'selesai'     => 'success',
                                    'booking'     => 'warning',
                                    'dibatalkan'  => 'secondary',
                                    default       => 'danger',
                                };
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
                                    <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                                        <a href="{{ route('mahasiswa.peminjaman.show', $item->id_peminjaman) }}"
                                           class="btn btn-sm btn-outline-success">
                                            Detail
                                        </a>
                                        @if($status === 'berlangsung')
                                            <a href="{{ route('mahasiswa.perpanjangan.create', ['id_peminjaman' => $item->id_peminjaman]) }}"
                                               class="btn btn-sm btn-primary">
                                                Ajukan Perpanjangan
                                            </a>
                                        @endif
                                        @if($status === 'booking')
                                            <form method="POST"
                                                  action="{{ route('mahasiswa.peminjaman.cancel', $item->id_peminjaman) }}"
                                                  onsubmit="return confirm('Batalkan booking ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Batal
                                                </button>
                                            </form>
                                        @endif
                                    </div>
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

    {{-- aktifkan kelas background khusus halaman ini --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('bg-peminjaman-mahasiswa');
        });
    </script>
@endif
@endsection
