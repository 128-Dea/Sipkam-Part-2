<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Peminjaman;
use App\Models\Qr;
use App\Models\SerahTerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SerahTerimaController extends Controller
{
    public function index()
    {
        $serahTerima = SerahTerima::with(['peminjaman', 'penggunaLama', 'penggunaBaru'])->orderByDesc('id_serah_terima')->get();

        return view('serah_terima.index', compact('serahTerima'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'barang'])
            ->whereDoesntHave('pengembalian')
            ->get();
        $pengguna = Pengguna::all();

        return view('serah_terima.create', compact('peminjaman', 'pengguna'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'pengguna_lama' => 'required|exists:pengguna,id_pengguna',
            'pengguna_baru' => 'required|different:pengguna_lama|exists:pengguna,id_pengguna',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($data['id_peminjaman']);

        if ($peminjaman->id_pengguna !== (int) $data['pengguna_lama']) {
            return back()->withErrors([
                'pengguna_lama' => 'Pengguna lama tidak sesuai dengan peminjaman. '
            ])->withInput();
        }

        DB::transaction(function () use ($data, $peminjaman) {
            $serahTerima = SerahTerima::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'pengguna_lama' => $data['pengguna_lama'],
                'pengguna_baru' => $data['pengguna_baru'],
                'catatan' => $data['catatan'] ?? null,
                'waktu' => now(),
                'status_persetujuan' => 'menunggu',
            ]);

            $peminjaman->update(['id_pengguna' => $data['pengguna_baru']]);

            Qr::create([
                'qr_code' => 'ST-' . $serahTerima->id_serah_terima . '-' . Str::upper(Str::random(8)),
                'jenis_transaksi' => 'serah_terima',
                'id_peminjaman' => $serahTerima->id_peminjaman,
                'id_serah_terima' => $serahTerima->id_serah_terima,
                'dibuat_pada' => now(),
                'is_active' => true,
            ]);
        });

        // Serah terima hanya dibuat oleh mahasiswa; arahkan kembali ke dashboard mahasiswa
        return redirect()->route('mahasiswa.dashboard')->with('success', 'Serah terima berhasil diproses');
    }
}
