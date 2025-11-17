<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $service = Service::with('keluhan.peminjaman.barang')->orderByDesc('id_service')->get();

        return view('service.index', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'status' => 'required|in:mengantri,diperbaiki,selesai',
        ]);

        $service->update($data);

        if ($data['status'] === 'selesai' && $service->keluhan && $service->keluhan->peminjaman && $service->keluhan->peminjaman->barang) {
            $service->keluhan->peminjaman->barang->update(['status' => 'tersedia']);
        }

        return redirect()->route('service.index')->with('success', 'Status service diperbarui');
    }
}
