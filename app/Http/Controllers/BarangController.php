<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $user = auth()->user();
        $isTrashView = $user && $user->role === 'petugas' && $request->boolean('trash');

        $query = Barang::with(['kategori', 'peminjaman']);

        // Trash view (petugas)
        if ($isTrashView) {
            $query->onlyTrashed();
        }

        // Search
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // ================= PETUGAS =================
        if ($user && $user->role === 'petugas') {
            $barang = $query->get();

            // Optional filter status otomatis
            if ($status = $request->input('status')) {
                $barang = $barang
                    ->filter(fn (Barang $item) => $item->status_otomatis === $status)
                    ->values();
            }
        }
        // ================= MAHASISWA =================
        else {
            // 🔑 PERBAIKAN UTAMA:
            // Mahasiswa tetap melihat semua barang
            $barang = $query->get();
        }

        return view('barang.index', compact('barang', 'isTrashView'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $kategori = Kategori::all();
        return view('barang.create', compact('kategori'));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'nullable|string|max:20|unique:barang,kode_barang',
            'harga'       => 'nullable|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        // Pastikan key selalu ada sebelum dipakai (nullable input bisa hilang dari array)
        $data['kode_barang'] = $data['kode_barang'] ?? null;
        if (!$data['kode_barang']) {
            $data['kode_barang'] = $this->generateKodeBarang();
        }
        $data['harga']       = $data['harga'] ?? null;
        $data['status']      = $data['stok'] > 0 ? 'tersedia' : 'habis';

        if ($request->hasFile('foto_barang')) {
            $data['foto_path'] = $request->file('foto_barang')->store('barang', 'public');
        }

        Barang::create($data);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    // =========================
    // SHOW
    // =========================
    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'peminjaman']);
        return view('barang.show', compact('barang'));
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $barang)
    {
        $barang = $barang instanceof Barang
            ? $barang
            : Barang::findOrFail($barang);

        $data = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'nullable|string|max:20|unique:barang,kode_barang,' . $barang->id_barang . ',id_barang',
            'harga'       => 'nullable|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        $data['kode_barang'] = $data['kode_barang'] ?? null;
        if (!$data['kode_barang']) {
            $data['kode_barang'] = $barang->kode_barang ?? $this->generateKodeBarang();
        }

        $data['harga'] = $data['harga'] ?? $barang->harga;
        $data['stok']  = $data['stok'] ?? $barang->stok;

        // Status manual hanya dikontrol otomatis
        if (!in_array($barang->status, ['rusak', 'hilang', 'nonaktif'], true)) {
            $data['status'] = $data['stok'] > 0 ? 'tersedia' : 'habis';
        } else {
            $data['status'] = $barang->status;
        }

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_path) {
                Storage::disk('public')->delete($barang->foto_path);
            }
            $data['foto_path'] = $request->file('foto_barang')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    // =========================
    // DELETE (SOFT)
    // =========================
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    // RESTORE

    public function restore(int $id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);
        $barang->restore();

        return redirect()->route('barang.index', ['trash' => 1])
            ->with('success', 'Barang berhasil dipulihkan');
    }

    // FORCE DELETE
    public function forceDestroy(int $id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);

        if ($barang->foto_path) {
            Storage::disk('public')->delete($barang->foto_path);
        }

        $barang->forceDelete();

        return redirect()->route('barang.index', ['trash' => 1])
            ->with('success', 'Barang dihapus permanen');
    }

  
    // STOK TAMBAH

    public function stokTambah(Request $request, Barang $barang)
    {
        $jumlah = max(1, (int) $request->input('jumlah', 1));
        $barang->stok += $jumlah;

        if (!in_array($barang->status, ['rusak', 'hilang', 'nonaktif'], true)) {
            $barang->status = 'tersedia';
        }

        $barang->save();

        return back()->with('success', 'Stok berhasil ditambah');
    }


    // STOK KURANG

    public function stokKurang(Request $request, Barang $barang)
    {
        $jumlah = max(1, (int) $request->input('jumlah', 1));
        $barang->stok = max(0, $barang->stok - $jumlah);

        if (!in_array($barang->status, ['rusak', 'hilang', 'nonaktif'], true) && $barang->stok === 0) {
            $barang->status = 'habis';
        }

        $barang->save();

        return back()->with('success', 'Stok berhasil dikurangi');
    }

    // HELPER

    private function generateKodeBarang(): string
    {
        $next = (Barang::max('id_barang') ?? 0) + 1;
        return 'BRG-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
