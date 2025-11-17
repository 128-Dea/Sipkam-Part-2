<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KeluhanController extends Controller
{
    public function index()
    {
        $keluhan = Keluhan::with(['pengguna', 'peminjaman.barang'])->orderByDesc('id_keluhan')->get();

        return view('keluhan.index', compact('keluhan'));
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        $peminjaman = Peminjaman::with('barang')->get();

        return view('keluhan.create', compact('pengguna', 'peminjaman'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'keluhan' => 'required|string',
            'foto_keluhan' => 'nullable|image|max:2048',
        ]);

        $peminjaman = Peminjaman::findOrFail($data['id_peminjaman']);

        // Pastikan hanya pemilik peminjaman yang bisa membuat keluhan
        if ($peminjaman->id_pengguna !== auth()->id()) {
            return back()->withErrors(['id_peminjaman' => 'Anda tidak memiliki akses untuk membuat keluhan pada peminjaman ini.']);
        }

        if ($request->hasFile('foto_keluhan')) {
            $data['foto_path'] = $request->file('foto_keluhan')->store('keluhan', 'public');
        }

        $keluhan = DB::transaction(function () use ($data) {
            return Keluhan::create([
                'id_pengguna' => auth()->id(),
                'id_peminjaman' => $data['id_peminjaman'],
                'keluhan' => $data['keluhan'],
                'foto_path' => $data['foto_path'] ?? null,
            ]);
        });

        if ($keluhan->peminjaman && $keluhan->peminjaman->barang) {
            $keluhanText = Str::lower($keluhan->keluhan);
            if (Str::contains($keluhanText, 'rusak')) {
                $keluhan->peminjaman->barang->update(['status' => 'dalam_service']);
            }
        }

        return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil dicatat');
    }

    public function show(Keluhan $keluhan)
    {
        // Pastikan hanya pemilik keluhan atau petugas yang bisa melihat detail
        if (auth()->user()->role === 'mahasiswa' && $keluhan->id_pengguna !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat keluhan ini.');
        }

        return view('keluhan.show', compact('keluhan'));
    }
}
