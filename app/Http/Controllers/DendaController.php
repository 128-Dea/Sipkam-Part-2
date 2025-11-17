<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index()
    {
        $denda = Denda::with('peminjaman.pengguna')->orderByDesc('id_denda')->get();

        return view('denda.index', compact('denda'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with('pengguna')->get();

        return view('denda.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'jenis' => 'required|string|max:100',
            'total_denda' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data['status_pembayaran'] = 'belum';

        Denda::create($data);

        return redirect()->route('denda.index')->with('success', 'Denda berhasil ditambahkan');
    }

    public function update(Request $request, Denda $denda)
    {
        $data = $request->validate([
            'status_pembayaran' => 'required|in:belum,sudah',
        ]);

        $denda->update($data);

        return redirect()->route('denda.index')->with('success', 'Status pembayaran diperbarui');
    }
}
