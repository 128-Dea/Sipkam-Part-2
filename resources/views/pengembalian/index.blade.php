@extends('layouts.app')

@section('content')
<style>
    :root {
        --sipkam-dark-1: #051F20;
        --sipkam-dark-2: #0B2B26;
        --sipkam-dark-3: #163832;
        --sipkam-soft:   #8EB69B;
        --sipkam-light:  #DAF1DE;
    }

    .sipkam-pengembalian-page {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        min-height: calc(100vh - 64px);
        padding: 40px 32px 56px;
        margin-top: -24px;
        margin-bottom: -24px;
        display: flex;
        align-items: flex-start;
    }

    .sipkam-pengembalian-shell {
        flex: 1;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .sipkam-pengembalian-banner {
        margin-bottom: 1.5rem;
        border-radius: 24px;
        background: linear-gradient(135deg, #051F20, #0F3533);
        padding: 1.9rem 2.25rem;
        box-shadow: 0 22px 50px rgba(3, 26, 23, 0.35);
    }

    .sipkam-pengembalian-banner h1 {
        color: var(--sipkam-light);
        font-weight: 600;
        font-size: 2.3rem;
        margin-bottom: 0.4rem;
    }

    .sipkam-pengembalian-banner .text-muted {
        color: rgba(233, 247, 240, 0.85) !important;
    }

    .sipkam-pengembalian-page .card {
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .sipkam-pengembalian-page .table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: none;
        background: linear-gradient(135deg, var(--sipkam-dark-2), var(--sipkam-dark-3)) !important;
        color: #e5f9ee;
    }

    .sipkam-pengembalian-page .table tbody td {
        border-color: #e2ece5;
    }

    .sipkam-pengembalian-page .badge {
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .sipkam-pengembalian-page .btn {
        border-radius: 999px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        box-shadow: 0 14px 28px rgba(5, 31, 32, 0.45);
    }
</style>

<div class="sipkam-pengembalian-page">
    <div class="sipkam-pengembalian-shell">
        <div class="sipkam-pengembalian-banner">
            <div class="d-flex justify-content-between flex-wrap align-items-center gap-3">
                <div>
                    <p class="text-muted small mb-1">Dashboard / <span class="fw-semibold text-light">Pengembalian</span></p>
                    <h1 class="mb-0">Pengembalian Barang</h1>
                    <small class="text-muted">Daftar seluruh pengembalian barang oleh mahasiswa</small>
                </div>
                <a href="{{ route('petugas.pengembalian.scan') }}" class="btn btn-primary">
                    Scan QR Pengembalian
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Peminjam</th>
                        <th>Waktu Pengembalian</th>
                        <th>Denda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $item)
                        @php
                            $peminjaman = $item->peminjaman;
                            $totalDenda = $peminjaman?->denda?->sum('total_denda') ?? 0;
                            $catatanAsli = $item->catatan ?? '-';
                            $catatanBersih = preg_replace('/\\|\\s*Foto:\\s*[^|]+/i', '', $catatanAsli);
                            $fotoPath = null;
                            if (preg_match('/Foto:\\s*([^|]+)/i', $catatanAsli, $matches)) {
                                $fotoPath = trim($matches[1]);
                            }
                            $fotoUrl = $fotoPath ? \Illuminate\Support\Facades\Storage::url($fotoPath) : '';
                        @endphp
                        <tr
                            data-row
                            data-nama="{{ $peminjaman->pengguna->nama ?? '-' }}"
                            data-email="{{ $peminjaman->pengguna->email ?? '-' }}"
                            data-barang="{{ $peminjaman->barang->nama_barang ?? '-' }}"
                            data-kode="{{ $peminjaman->barang->kode_barang ?? '-' }}"
                            data-pinjam="{{ optional($peminjaman?->waktu_awal ? \Carbon\Carbon::parse($peminjaman->waktu_awal) : null)?->translatedFormat('d M Y H:i') ?? '-' }}"
                            data-kembali="{{ optional($item->waktu_pengembalian)?->translatedFormat('d M Y H:i') ?? '-' }}"
                            data-denda="{{ $totalDenda > 0 ? 'Rp ' . number_format($totalDenda, 0, ',', '.') : 'Tidak ada' }}"
                            data-catatan="{{ trim($catatanBersih) ?: '-' }}"
                            data-foto="{{ $fotoUrl }}"
                        >
                            <td>#{{ $item->id_pengembalian }}</td>
                            <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                            <td>
                                {{ $peminjaman->pengguna->nama ?? '-' }}<br>
                                @if(!empty($peminjaman->pengguna?->email))
                                    <small class="text-muted">{{ $peminjaman->pengguna->email }}</small>
                                @endif
                            </td>
                            <td>{{ optional($item->waktu_pengembalian)->format('d M Y H:i') }}</td>
                            <td>
                                @if($totalDenda > 0)
                                    <span class="badge bg-danger">Ada (Rp {{ number_format($totalDenda, 0, ',', '.') }})</span>
                                @else
                                    <span class="badge bg-success">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada pengembalian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengembalian</h5>
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
                            <div class="fw-semibold text-muted">Total Denda</div>
                            <div id="d-denda" class="fw-semibold text-danger"></div>
                            <div class="fw-semibold text-muted mt-3">Catatan</div>
                            <div id="d-catatan" class="text-muted"></div>
                            <div id="d-foto" class="mt-3"></div>
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
                if (!row) return;
                document.getElementById('d-nama').textContent = row.dataset.nama || '-';
                document.getElementById('d-email').textContent = row.dataset.email || '-';
                document.getElementById('d-barang').textContent = row.dataset.barang || '-';
                document.getElementById('d-kode').textContent = row.dataset.kode || '-';
                document.getElementById('d-pinjam').textContent = row.dataset.pinjam || '-';
                document.getElementById('d-kembali').textContent = row.dataset.kembali || '-';
                document.getElementById('d-denda').textContent = row.dataset.denda || '-';
                document.getElementById('d-catatan').textContent = row.dataset.catatan || '-';
                const fotoContainer = document.getElementById('d-foto');
                fotoContainer.innerHTML = '';
                if (row.dataset.foto) {
                    const img = document.createElement('img');
                    img.src = row.dataset.foto;
                    img.alt = 'Foto kerusakan';
                    img.className = 'img-fluid rounded border';
                    fotoContainer.appendChild(img);
                    const link = document.createElement('a');
                    link.href = row.dataset.foto;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    link.className = 'd-block small mt-2';
                    link.textContent = 'Lihat ukuran penuh';
                    fotoContainer.appendChild(link);
                }
                detailModal.show();
            });
        });
    });
</script>
@endsection
