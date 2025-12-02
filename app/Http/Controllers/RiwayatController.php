<?php

namespace App\Http\Controllers;

use App\Models\riwayat;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class riwayatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $riwayat = riwayat::with([
                'pengembalian.peminjaman.pengguna',
                'pengembalian.peminjaman.barang',
                'pengembalian.peminjaman.denda',
            ])
            ->whereHas('pengembalian.peminjaman', function ($q) use ($userId) {
                $q->where('id_pengguna', $userId);
            })
            ->orderByDesc('id_riwayat')
            ->get();

        return view('riwayat.index', compact('riwayat'));
    }

    public function show(riwayat $riwayat)
    {
        $userId = auth()->id();

        // Batasi akses detail riwayat hanya untuk pemilik atau petugas
        if (auth()->user()->role === 'mahasiswa' && optional($riwayat->pengembalian->peminjaman)->id_pengguna !== $userId) {
            abort(403);
        }

        $riwayat->load(['pengembalian.peminjaman.pengguna', 'pengembalian.peminjaman.barang', 'pengembalian.peminjaman.denda']);

        return view('riwayat.show', compact('riwayat'));
    }

    /**
     * riwayat transaksi untuk petugas (peminjaman selesai).
     */
    public function petugas(Request $request)
    {
        $filters = $this->extractFilters($request);
        $pengembalian = $this->queryHistory($filters)->get();

        $totalDenda = $pengembalian->reduce(function ($carry, $item) {
            return $carry + ($item->peminjaman?->denda?->sum('total_denda') ?? 0);
        }, 0);

        return view('riwayat.petugas', compact('pengembalian', 'filters', 'totalDenda'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $pengembalian = $this->queryHistory($this->extractFilters($request))->get();
        $filename = 'riwayat-transaksi.csv';

        return response()->streamDownload(function () use ($pengembalian) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama Mahasiswa', 'Barang', 'Tanggal Pinjam', 'Tanggal Pengembalian', 'Kondisi', 'Total Denda']);
            foreach ($pengembalian as $item) {
                $p = $item->peminjaman;
                $nama = $p?->pengguna?->nama ?? '-';
                $barang = $p?->barang?->nama_barang ?? '-';
                $pinjam = $p?->waktu_awal;
                $kembali = $item->waktu_pengembalian;
                $kondisi = $p?->barang?->status ?? 'baik';
                $denda = $p?->denda?->sum('total_denda') ?? 0;
                fputcsv($handle, [
                    $nama,
                    $barang,
                    $pinjam,
                    $kembali,
                    $kondisi,
                    $denda,
                ]);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportHtml(Request $request)
    {
        $pengembalian = $this->queryHistory($this->extractFilters($request))->get();
        $totalDenda = $pengembalian->reduce(function ($carry, $item) {
            return $carry + ($item->peminjaman?->denda?->sum('total_denda') ?? 0);
        }, 0);

        $html = view('riwayat.export', compact('pengembalian', 'totalDenda'))->render();

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, 'riwayat-transaksi.html', [
            'Content-Type' => 'text/html',
        ]);
    }

    protected function queryHistory(array $filters)
    {
        $kondisi = $filters['kondisi'] ?? null;
        $search  = $filters['search'] ?? null;

        return Pengembalian::with([
                'peminjaman.pengguna',
                'peminjaman.barang',
                'peminjaman.denda',
            ])
            ->whereHas('peminjaman', function ($q) {
                $q->whereIn('status', ['selesai', 'dibatalkan']);
            })
            ->when($kondisi, function ($q, $kondisi) {
                $q->whereHas('peminjaman', function ($qp) use ($kondisi) {
                    $qp->whereHas('barang', function ($qb) use ($kondisi) {
                        $qb->where('status', $kondisi);
                    });
                });
            })
            ->when($search, function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->whereHas('peminjaman', function ($qp) use ($search) {
                        $qp->whereHas('pengguna', function ($qu) use ($search) {
                            $qu->where('nama', 'like', "%{$search}%");
                        });
                    })->orWhereHas('peminjaman', function ($qp) use ($search) {
                        $qp->whereHas('barang', function ($qb) use ($search) {
                            $qb->where('nama_barang', 'like', "%{$search}%");
                        });
                    });
                });
            })
            ->orderByDesc('waktu_pengembalian');
    }

    /**
     * Ambil dan normalkan filter riwayat petugas agar selalu konsisten.
     */
    protected function extractFilters(Request $request): array
    {
        $kondisi = trim((string) $request->query('kondisi', ''));
        $search  = trim((string) $request->query('search', ''));

        $allowedKondisi = ['tersedia', 'dalam_service', 'hilang'];
        if (!in_array($kondisi, $allowedKondisi, true)) {
            $kondisi = null;
        }

        return [
            'kondisi' => $kondisi,
            'search'  => $search ?: null,
        ];
    }
}
