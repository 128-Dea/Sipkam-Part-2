@extends('layouts.app')

@section('content')
@php
    $filters = $filters ?? ['from' => null, 'to' => null, 'kondisi' => null, 'search' => null];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Histori Transaksi 📁</h1>
        <small class="text-muted">Arsip peminjaman yang sudah selesai</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('petugas.riwayat.export.csv', $filters) }}" class="btn btn-outline-primary">Download Excel (CSV)</a>
        <a href="{{ route('petugas.riwayat.export.html', $filters) }}" class="btn btn-outline-secondary">Download PDF (HTML)</a>
    </div>
    <span class="badge bg-primary bg-opacity-10 text-primary">Total denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label form-label-modern">Dari</label>
                <input type="date" id="filter-from" class="form-control form-control-modern" value="{{ $filters['from'] }}">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-modern">Sampai</label>
                <input type="date" id="filter-to" class="form-control form-control-modern" value="{{ $filters['to'] }}">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-modern">Kondisi</label>
                <select id="filter-kondisi" class="form-select form-control-modern">
                    <option value="">Semua</option>
                    <option value="tersedia" {{ $filters['kondisi']==='tersedia' ? 'selected' : '' }}>🟢 Baik</option>
                    <option value="service" {{ $filters['kondisi']==='service' ? 'selected' : '' }}>🟡 Service / Rusak</option>
                    <option value="hilang" {{ $filters['kondisi']==='hilang' ? 'selected' : '' }}>🔴 Hilang</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-modern">Cari Mahasiswa / Barang</label>
                <input type="text" id="filter-search" class="form-control form-control-modern" value="{{ $filters['search'] }}" placeholder="ketik nama atau barang">
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="histori-table">
            <thead class="table-light">
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Kondisi Barang</th>
                    <th>Total Denda</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $item)
                    @php
                        $p = $item->peminjaman;
                        $kondisi = $p?->barang?->status ?? 'tersedia';
                        $kondisiLabel = '🟢 Baik';
                        $badge = 'success';
                        if ($kondisi === 'service') { $kondisiLabel = '🟡 Service / Rusak'; $badge = 'warning'; }
                        if ($kondisi === 'hilang') { $kondisiLabel = '🔴 Hilang'; $badge = 'danger'; }
                        $denda = $p?->denda?->sum('total_denda') ?? 0;
                    @endphp
                    <tr data-row
                        data-nama="{{ $p?->pengguna?->nama ?? '-' }}"
                        data-email="{{ $p?->pengguna?->email ?? '-' }}"
                        data-barang="{{ $p?->barang?->nama_barang ?? '-' }}"
                        data-kode="{{ $p?->barang?->kode_barang ?? '-' }}"
                        data-pinjam="{{ \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y H:i') }}"
                        data-kembali="{{ \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y H:i') }}"
                        data-kondisi="{{ $kondisiLabel }}"
                        data-denda="Rp {{ number_format($denda, 0, ',', '.') }}"
                        data-catatan="{{ $item->catatan ?? '-' }}"
                    >
                        <td>{{ $p?->pengguna?->nama ?? '-' }}</td>
                        <td>{{ $p?->barang?->nama_barang ?? '-' }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y') }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y') }}</td>
                        <td><span class="badge bg-{{ $badge }}">{{ $kondisiLabel }}</span></td>
                        <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada histori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted">Mahasiswa</div>
                            <div id="d-nama" class="fw-semibold"></div>
                            <small class="text-muted" id="d-email"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted">Barang</div>
                            <div id="d-barang" class="fw-semibold"></div>
                            <small class="text-muted" id="d-kode"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100">
                            <div class="fw-semibold text-muted">Tanggal Pinjam</div>
                            <div id="d-pinjam" class="fw-semibold"></div>
                            <div class="fw-semibold text-muted mt-3">Tanggal Pengembalian</div>
                            <div id="d-kembali" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100">
                            <div class="fw-semibold text-muted">Kondisi Barang</div>
                            <div id="d-kondisi" class="fw-semibold"></div>
                            <div class="fw-semibold text-muted mt-3">Total Denda</div>
                            <div id="d-denda" class="fw-semibold text-danger"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded bg-white shadow-sm">
                            <div class="fw-semibold text-muted">Catatan / Riwayat</div>
                            <p class="mb-0" id="d-catatan"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const inputs = {
            from: document.getElementById('filter-from'),
            to: document.getElementById('filter-to'),
            kondisi: document.getElementById('filter-kondisi'),
            search: document.getElementById('filter-search'),
        };

        Object.values(inputs).forEach(el => {
            el?.addEventListener('change', applyFilters);
            el?.addEventListener('input', applyFilters);
        });

        function applyFilters() {
            params.set('from', inputs.from.value || '');
            params.set('to', inputs.to.value || '');
            params.set('kondisi', inputs.kondisi.value || '');
            params.set('search', inputs.search.value || '');
            const query = params.toString().replace(/(&?[^=]*=)(?=&|$)/g, '');
            window.location = '{{ route('petugas.riwayat.index') }}' + (query ? '?' + query : '');
        }

        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.querySelectorAll('[data-row] .btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                document.getElementById('d-nama').textContent = row.dataset.nama;
                document.getElementById('d-email').textContent = row.dataset.email;
                document.getElementById('d-barang').textContent = row.dataset.barang;
                document.getElementById('d-kode').textContent = row.dataset.kode;
                document.getElementById('d-pinjam').textContent = row.dataset.pinjam;
                document.getElementById('d-kembali').textContent = row.dataset.kembali;
                document.getElementById('d-kondisi').textContent = row.dataset.kondisi;
                document.getElementById('d-denda').textContent = row.dataset.denda;
                document.getElementById('d-catatan').textContent = row.dataset.catatan;
                detailModal.show();
            });
        });
    });
</script>
@endsection
