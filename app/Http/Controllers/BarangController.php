<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->get();

        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = Kategori::all();

        return view('barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:20',
            'harga' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:tersedia,dipinjam,rusak,dalam_service',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        $data['status'] = $data['status'] ?? 'tersedia';
        $data['harga'] = $data['harga'] ?? null;

        if ($request->hasFile('foto_barang')) {
            $data['foto_path'] = $request->file('foto_barang')->store('barang', 'public');
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();

        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:20',
            'harga' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:tersedia,dipinjam,rusak,dalam_service',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        $data['status'] = $data['status'] ?? $barang->status;
        $data['harga'] = $data['harga'] ?? $barang->harga;

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_path) {
                Storage::disk('public')->delete($barang->foto_path);
            }
            $data['foto_path'] = $request->file('foto_barang')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto_path) {
            Storage::disk('public')->delete($barang->foto_path);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
