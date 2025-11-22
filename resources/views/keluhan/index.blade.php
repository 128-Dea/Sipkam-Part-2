@extends('layouts.app')

@section('content')
@php
    $scope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp
<<<<<<< Updated upstream

{{-- ===== STYLE KHUSUS HALAMAN KELUHAN ===== --}}
<style>
    :root {
        --sipkam-complaint-accent: #3b82f6; /* biru tombol */
        --sipkam-complaint-accent-soft: rgba(59,130,246,0.35);
    }

    /* Background full mengikuti tema (light / dark) */
    .sipkam-complaint-page {
        min-height: 100vh;
        margin: -24px -32px -40px -32px; /* tarik keluar padding layout */
        padding: 24px 32px 40px;
        display: block;                  /* FULL width, bukan flex center lagi */
    }

    body.sipkam-light .sipkam-complaint-page {
        background: linear-gradient(135deg,#e0f2fe 0%,#f9fafb 40%,#dcfce7 100%);
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-complaint-page {
        background: radial-gradient(circle at top,#020617 0%,#020617 40%,#020617 100%);
        color: #e5e7eb;
    }

    .sipkam-complaint-shell {
        width: 100%;
        max-width: 100%;   /* << ini yang bikin wrapper mentok kanan kiri 100% */
    }

    /* HEADER */
    .sipkam-complaint-header {
        margin-bottom: 1.5rem;
    }

    .sipkam-complaint-title {
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .sipkam-complaint-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .sipkam-complaint-btn {
        border-radius: 999px;
        padding: 0.45rem 1.7rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    body.sipkam-light .sipkam-complaint-btn {
        background: linear-gradient(135deg,#3b82f6,#06b6d4);
        color: #eff6ff;
        box-shadow: 0 12px 28px rgba(37,99,235,0.55);
    }

    body.sipkam-dark .sipkam-complaint-btn {
        background: linear-gradient(135deg,#3b82f6,#22c55e);
        color: #e5f2ff;
        box-shadow: 0 14px 36px rgba(37,99,235,0.8);
    }

    .sipkam-complaint-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 44px rgba(37,99,235,0.85);
    }

    /* CARD + GLOW */
    .sipkam-complaint-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
    }

    body.sipkam-light .sipkam-complaint-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(148,163,184,0.35);
        box-shadow: 0 22px 50px rgba(148,163,184,0.55);
    }

    body.sipkam-dark .sipkam-complaint-card {
        background: radial-gradient(circle at top left,#020617,#020617 55%,#020617 100%);
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 26px 70px rgba(0,0,0,0.95);
    }

    .sipkam-complaint-card::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -40px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: radial-gradient(circle at center,var(--sipkam-complaint-accent-soft),transparent 70%);
        filter: blur(2px);
        opacity: 0.9;
        pointer-events: none;
    }

    .sipkam-complaint-card .table-responsive {
        position: relative;
        z-index: 1;
    }

    /* TABLE */
    .sipkam-complaint-table {
        margin-bottom: 0;
    }

    .sipkam-complaint-table thead th {
        border-bottom: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding-top: 0.75rem;
        padding-bottom: 0.55rem;
        background: transparent;
    }

    body.sipkam-light .sipkam-complaint-table thead th {
        color: #64748b;
    }

    body.sipkam-dark .sipkam-complaint-table thead th {
        color: #a7f3d0;
    }

    .sipkam-complaint-table tbody td {
        vertical-align: middle;
        font-size: 0.9rem;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
    }

    .sipkam-complaint-table tbody tr + tr {
        border-top: 1px solid rgba(148,163,184,0.3);
    }

    body.sipkam-dark .sipkam-complaint-table tbody tr + tr {
        border-color: rgba(30,64,175,0.6);
    }

    .sipkam-complaint-table tbody tr:hover {
        background: rgba(15,23,42,0.02);
    }

    body.sipkam-dark .sipkam-complaint-table tbody tr:hover {
        background: rgba(15,23,42,0.8);
    }

    /* Foto keluhan */
    .sipkam-complaint-photo {
        max-height: 60px;
        border-radius: 0.6rem;
        object-fit: cover;
    }

    /* Tombol detail */
    .sipkam-complaint-table .btn-outline-primary {
        border-radius: 999px;
        font-size: 0.78rem;
        padding: 0.25rem 0.85rem;
    }

    /* Baris kosong */
    .sipkam-complaint-empty {
        padding: 2.3rem 0;
        font-size: 0.9rem;
    }

    @media (max-width: 767.98px) {
        .sipkam-complaint-page {
            margin: -16px -16px -24px -16px;
            padding: 16px 16px 24px;
        }
        .sipkam-complaint-shell {
            max-width: 100%;
        }
    }
</style>

<div class="sipkam-complaint-page">
    <div class="sipkam-complaint-shell">

        {{-- HEADER (logika tetap) --}}
        <div class="d-flex justify-content-between align-items-center mb-4 sipkam-complaint-header">
            <div>
                <h1 class="h3 mb-1 sipkam-complaint-title">Keluhan Mahasiswa</h1>
                <small class="sipkam-complaint-subtitle">Kelola laporan gangguan selama peminjaman</small>
            </div>
            @if($keluhanRouteScope === 'mahasiswa')
                <a href="{{ route($keluhanRouteScope . '.keluhan.create') }}"
                   class="btn btn-primary sipkam-complaint-btn">
                    Laporkan Keluhan
                </a>
            @endif
        </div>

        {{-- CARD + TABLE (LOGIKA TIDAK DIUBAH) --}}
        <div class="card border-0 shadow-sm sipkam-complaint-card">
            <div class="table-responsive">
                <table class="table table-striped mb-0 sipkam-complaint-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Barang</th>
                            <th>Keluhan</th>
                            <th>Pelapor</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keluhan as $item)
                            <tr>
                                <td>{{ $item->id_keluhan }}</td>
                                <td style="width: 100px;">
                                    @if($item->foto_url)
                                        <img src="{{ $item->foto_url }}" alt="Foto keluhan"
                                             class="img-thumbnail sipkam-complaint-photo">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ Str::limit($item->keluhan, 50) }}</td>
                                <td>{{ $item->pengguna->nama ?? '-' }}</td>
                                <td>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route($keluhanRouteScope . '.keluhan.show', $item->id_keluhan) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted sipkam-complaint-empty">
                                    Belum ada keluhan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

=======

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h3 mb-1">Keluhan Barang</h1>
        <small class="text-muted">Pantau laporan mahasiswa dan tindak lanjuti</small>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-control-modern">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🕓 Pending</option>
                <option value="ditangani" {{ request('status') === 'ditangani' ? 'selected' : '' }}>🔧 Ditangani</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>✔️ Selesai</option>
            </select>
            <button class="btn btn-modern btn-modern-primary" type="submit">Filter</button>
        </form>
        @if($scope === 'mahasiswa')
            <a href="{{ route($scope . '.keluhan.create') }}" class="btn btn-primary">Laporkan Keluhan</a>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Barang</th>
                    <th>Tanggal Keluhan</th>
                    <th>Isi / Detail</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keluhan as $item)
                    @php
                        $status = $item->status ?? 'pending';
                        $badge = 'warning';
                        $label = '🕓 Pending';
                        if ($status === 'ditangani') { $badge = 'warning'; $label = '🔧 Ditangani'; }
                        if ($status === 'selesai') { $badge = 'success'; $label = '✔️ Selesai'; }
                    @endphp
                    <tr
                        data-row
                        data-nama="{{ $item->pengguna->nama ?? '-' }}"
                        data-email="{{ $item->pengguna->email ?? '-' }}"
                        data-barang="{{ $item->peminjaman->barang->nama_barang ?? '-' }}"
                        data-keluhan="{{ $item->keluhan }}"
                        data-tanggal="{{ optional($item->created_at)->translatedFormat('d M Y H:i') ?? '-' }}"
                        data-status="{{ ucfirst($status) }}"
                        data-foto="{{ $item->foto_url ?? '' }}"
                        data-tindak="{{ $item->tindak_lanjut ?? '-' }}"
                    >
                        <td>{{ $item->pengguna->nama ?? '-' }}</td>
                        <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td class="text-nowrap">{{ optional($item->created_at)->translatedFormat('d M Y') ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->keluhan, 60) }}</td>
                        <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-primary btn-detail">🔍 Detail</button>
                                @if($scope === 'petugas' && $status === 'pending')
                                    <form method="POST" action="{{ route('petugas.keluhan.service', $item->id_keluhan) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">📩 Kirim ke Service</button>
                                    </form>
                                @endif
                                @if($scope === 'petugas' && in_array($status, ['pending','ditangani']))
                                    <form method="POST" action="{{ route('petugas.keluhan.selesai', $item->id_keluhan) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">✔️ Tandai Selesai</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada keluhan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
>>>>>>> Stashed changes
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Keluhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold mb-1">Mahasiswa</div>
                            <div id="d-nama"></div>
                            <small class="text-muted" id="d-email"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold mb-1">Barang</div>
                            <div id="d-barang"></div>
                            <small class="text-muted" id="d-status"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded bg-white shadow-sm">
                            <div class="fw-semibold mb-1">Deskripsi Keluhan</div>
                            <p class="mb-0" id="d-keluhan"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100">
                            <div class="fw-semibold mb-1">Tanggal Keluhan</div>
                            <div id="d-tanggal"></div>
                            <div class="fw-semibold mt-3 mb-1">Tindak Lanjut</div>
                            <p class="text-muted mb-0" id="d-tindak"></p>
                        </div>
                    </div>
                    <div class="col-md-6" id="d-foto-wrap" style="display:none;">
                        <div class="p-3 rounded bg-white shadow-sm h-100 text-center">
                            <div class="fw-semibold mb-2">Foto Keluhan</div>
                            <img id="d-foto" src="" alt="Foto keluhan" class="img-fluid rounded">
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
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.querySelectorAll('[data-row] .btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                document.getElementById('d-nama').textContent = row.dataset.nama;
                document.getElementById('d-email').textContent = row.dataset.email;
                document.getElementById('d-barang').textContent = row.dataset.barang;
                document.getElementById('d-status').textContent = row.dataset.status;
                document.getElementById('d-keluhan').textContent = row.dataset.keluhan;
                document.getElementById('d-tanggal').textContent = row.dataset.tanggal;
                document.getElementById('d-tindak').textContent = row.dataset.tindak;
                const foto = row.dataset.foto;
                const wrap = document.getElementById('d-foto-wrap');
                if (foto) {
                    document.getElementById('d-foto').src = foto;
                    wrap.style.display = '';
                } else {
                    wrap.style.display = 'none';
                }
                detailModal.show();
            });
        });
    });
</script>
@endsection
