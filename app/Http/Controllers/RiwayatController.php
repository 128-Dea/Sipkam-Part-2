<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayat = Riwayat::with(['pengembalian.peminjaman.pengguna', 'pengembalian.peminjaman.barang'])->orderByDesc('id_riwayat')->get();

        return view('riwayat.index', compact('riwayat'));
    }

    public function show(Riwayat $riwayat)
    {
        $riwayat->load(['pengembalian.peminjaman.pengguna', 'pengembalian.peminjaman.barang', 'pengembalian.peminjaman.denda']);

        return view('riwayat.show', compact('riwayat'));
    }
}
