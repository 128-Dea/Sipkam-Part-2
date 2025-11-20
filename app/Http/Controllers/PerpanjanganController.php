<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Perpanjangan;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    public function index()
    {
        $perpanjangan = Perpanjangan::with('peminjaman.pengguna')->orderByDesc('waktu_pengajuan')->get();

        return view('perpanjangan.index', compact('perpanjangan'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with('pengguna')->get();

        return view('perpanjangan.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'alasan' => 'required|string',
            'waktu_pengajuan' => 'required|date',
            'waktu_perpanjangan' => 'required|date|after:waktu_pengajuan',
        ]);

        $peminjaman = Peminjaman::findOrFail($data['id_peminjaman']);

        // Pastikan hanya pemilik peminjaman yang bisa mengajukan perpanjangan
        if ($peminjaman->id_pengguna !== auth()->id()) {
            return back()->withErrors(['id_peminjaman' => 'Anda tidak memiliki akses untuk memperpanjang peminjaman ini.']);
        }

        $data['status_persetujuan'] = 'menunggu';

        Perpanjangan::create($data);

        return redirect()->route('mahasiswa.perpanjangan.index')->with('success', 'Permohonan perpanjangan berhasil dikirim');
    }

    public function update(Request $request, Perpanjangan $perpanjangan)
    {
        $data = $request->validate([
            'status_persetujuan' => 'required|in:ditolak,disetujui,menunggu',
        ]);

        $perpanjangan->update($data);

        $routeScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';

        return redirect()->route($routeScope . '.perpanjangan.index')->with('success', 'Status perpanjangan diperbarui');
    }
}
