<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::with(['barang', 'pengguna'])->orderByDesc('id_notifikasi')->get();

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dihapus');
    }
}
