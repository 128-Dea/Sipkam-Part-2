<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Support\Carbon;

class MahasiswaController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $riwayatPeminjaman = Peminjaman::with(['barang.kategori', 'denda', 'keluhan'])
            ->where('id_pengguna', $userId)
            ->orderByDesc('waktu_awal')
            ->get();

        $statistik = [
            'aktif' => $riwayatPeminjaman->where('status', 'berlangsung')->count(),
            'selesai' => $riwayatPeminjaman->where('status', 'selesai')->count(),
        ];

        $statistikSekunder = [
            'total_peminjaman' => $riwayatPeminjaman->count(),
            'total_denda'      => $riwayatPeminjaman->sum(fn ($p) => $p->denda?->sum('total_denda') ?? 0),
            'total_keluhan'    => $riwayatPeminjaman->sum(fn ($p) => $p->keluhan?->count() ?? 0),
            'rata_durasi_jam'  => round($riwayatPeminjaman->avg(function ($p) {
                try {
                    return Carbon::parse($p->waktu_awal)->diffInHours(Carbon::parse($p->waktu_akhir));
                } catch (\Throwable) {
                    return 0;
                }
            }) ?? 0, 1),
        ];

        $aktivitas = $riwayatPeminjaman->take(5)->map(function ($peminjaman) {
            return [
                'judul' => $peminjaman->barang->nama_barang ?? 'Peminjaman',
                'deskripsi' => 'Status: ' . ucfirst($peminjaman->status ?? 'berlangsung'),
                'waktu' => Carbon::parse($peminjaman->waktu_awal)->translatedFormat('d M Y H:i'),
            ];
        });

        $barangDipinjam = $riwayatPeminjaman->filter(function ($peminjaman) {
            return $peminjaman->status === 'berlangsung';
        });

        return view('mahasiswa.dashboard', [
            'statistik' => $statistik,
            'statistikSekunder' => $statistikSekunder,
            'aktivitas' => $aktivitas,
            'barangDipinjam' => $barangDipinjam,
        ]);
    }
}
