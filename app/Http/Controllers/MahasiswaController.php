<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Support\Carbon;

class MahasiswaController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $riwayatPeminjaman = Peminjaman::with(['barang.kategori'])
            ->where('id_pengguna', $userId)
            ->orderByDesc('waktu_awal')
            ->get();

        $statistik = [
            'aktif' => $riwayatPeminjaman->where('status', 'berlangsung')->count(),
            'menunggu' => $riwayatPeminjaman->where('status', 'menunggu')->count(),
            'selesai' => $riwayatPeminjaman->where('status', 'selesai')->count(),
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
            'aktivitas' => $aktivitas,
            'barangDipinjam' => $barangDipinjam,
        ]);
    }
}
