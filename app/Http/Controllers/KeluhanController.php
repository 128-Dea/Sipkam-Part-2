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
    public function index(Request $request)
    {
        $status = $request->query('status');

        $keluhan = Keluhan::with(['pengguna', 'peminjaman.barang', 'service'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('id_keluhan')
            ->get();

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
        $authPenggunaId = $this->resolveAuthPenggunaId();

        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'keluhan' => 'required|string',
            'foto_keluhan' => 'nullable|image|max:2048',
        ]);

        $peminjaman = Peminjaman::findOrFail($data['id_peminjaman']);

        // Pastikan hanya pemilik peminjaman yang bisa membuat keluhan
        if ($authPenggunaId === null || $peminjaman->id_pengguna !== $authPenggunaId) {
            return back()->withErrors(['id_peminjaman' => 'Anda tidak memiliki akses untuk membuat keluhan pada peminjaman ini.']);
        }

        if ($request->hasFile('foto_keluhan')) {
            $data['foto_path'] = $request->file('foto_keluhan')->store('keluhan', 'public');
        }

        $keluhan = DB::transaction(function () use ($data) {
            return Keluhan::create([
                'id_pengguna' => $this->resolveAuthPenggunaId(),
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

        return redirect()->route('mahasiswa.keluhan.index')->with('success', 'Keluhan berhasil dicatat');
    }

    public function show(Keluhan $keluhan)
    {
        // Pastikan hanya pemilik keluhan atau petugas yang bisa melihat detail
        if (auth()->user()->role === 'mahasiswa' && $keluhan->id_pengguna !== $this->resolveAuthPenggunaId()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat keluhan ini.');
        }

        return view('keluhan.show', compact('keluhan'));
    }

    public function kirimService(Keluhan $keluhan)
    {
        if (auth()->user()->role !== 'petugas') {
            abort(403);
        }

        DB::transaction(function () use ($keluhan) {
            $keluhan->update([
                'status' => 'ditangani',
                'tindak_lanjut' => 'Dikirim ke service',
                'handled_at' => now(),
            ]);

            if ($keluhan->peminjaman?->barang) {
                $keluhan->peminjaman->barang->update(['status' => 'service']);
            }

            if (!$keluhan->service) {
                \App\Models\Service::create([
                    'id_keluhan' => $keluhan->id_keluhan,
                    'status' => 'mengantri',
                ]);
            }
        });

        return back()->with('success', 'Keluhan dikirim ke service dan status diperbarui.');
    }

    public function tandaiSelesai(Keluhan $keluhan)
    {
        if (auth()->user()->role !== 'petugas') {
            abort(403);
        }

        DB::transaction(function () use ($keluhan) {
            $keluhan->update([
                'status' => 'selesai',
                'tindak_lanjut' => 'Keluhan ditandai selesai',
                'handled_at' => now(),
            ]);

            if ($keluhan->service) {
                $keluhan->service->update(['status' => 'selesai']);
            }

            if ($keluhan->peminjaman?->barang && $keluhan->peminjaman->barang->status === 'service') {
                $keluhan->peminjaman->barang->update(['status' => 'tersedia']);
            }
        });

        return back()->with('success', 'Keluhan ditandai selesai.');
    }

    protected function resolveAuthPenggunaId(): ?int
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        $pengguna = Pengguna::find($user->id);

        if (!$pengguna && $user->email) {
            $pengguna = Pengguna::where('email', $user->email)->first();
        }

        if (!$pengguna) {
            $nomorHp = $user->phone ?? $user->nomor_hp ?? '0';
            $pengguna = new Pengguna();
            $pengguna->id_pengguna = $user->id;
            $pengguna->nama = $user->name ?? 'Pengguna';
            $pengguna->email = $user->email;
            $pengguna->nomor_hp = $nomorHp;
            $pengguna->role = $user->role ?? 'mahasiswa';
            $pengguna->save();
        }

        return $pengguna->id_pengguna;
    }
}
