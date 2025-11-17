<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with(['peminjaman.pengguna', 'peminjaman.barang'])->orderByDesc('waktu_pengembalian')->get();

        return view('pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with('barang')
            ->whereDoesntHave('pengembalian')
            ->whereHas('barang', fn ($query) => $query->where('status', 'dipinjam'))
            ->get();

        return view('pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'waktu_pengembalian' => 'required|date',
            'catatan' => 'nullable|string',
            'biaya_rusak' => 'nullable|numeric|min:0',
            'biaya_hilang' => 'nullable|numeric|min:0',
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($data['id_peminjaman']);

        DB::transaction(function () use ($data, $peminjaman) {
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $data['id_peminjaman'],
                'waktu_pengembalian' => $data['waktu_pengembalian'],
                'catatan' => $data['catatan'],
            ]);

            // Hitung denda terlambat
            $waktuAkhir = strtotime($peminjaman->waktu_akhir);
            $waktuPengembalian = strtotime($data['waktu_pengembalian']);
            $terlambatMenit = max(0, ($waktuPengembalian - $waktuAkhir) / 60);
            $dendaTerlambat = $terlambatMenit * 1000; // 1000 per menit

            $totalDenda = $dendaTerlambat;

            if ($data['biaya_rusak']) {
                $totalDenda += $data['biaya_rusak'];
            }

            if ($data['biaya_hilang']) {
                $totalDenda += $data['biaya_hilang'];
            }

            if ($totalDenda > 0) {
                Denda::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'jenis' => 'pengembalian',
                    'total_denda' => $totalDenda,
                    'status_pembayaran' => 'belum_dibayar',
                    'keterangan' => 'Denda pengembalian barang',
                ]);
            }

            // Update QR menjadi tidak aktif
            if ($peminjaman->qr) {
                $peminjaman->qr->update(['is_active' => false]);
            }

            // Update status peminjaman dan barang
            $peminjaman->update(['status' => 'selesai']);
            if ($peminjaman->barang) {
                $peminjaman->barang->update(['status' => 'tersedia']);
            }

            Riwayat::create([
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'serah_terima' => 'tidak',
                'denda' => $totalDenda,
            ]);
        });

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diproses');
    }
}
