<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Schema;

class NotifikasiController extends Controller
{
    public function index()
    {
        $query = Notifikasi::with(['barang', 'pengguna'])->orderByDesc('id_notifikasi');

        if (auth()->check()) {
            $role = auth()->user()->role ?? null;

            if ($role === 'mahasiswa') {
                $penggunaId = $this->resolveAuthPenggunaId();
                $query->where('id_pengguna', $penggunaId);
            } elseif ($role === 'petugas') {
                // Petugas lihat notifikasi yang ditujukan ke petugas (id_pengguna null)
                // atau jenis tertentu yang mengandung info pengaju (perpanjangan_diajukan)
                $query->where(function ($q) {
                    $q->whereNull('id_pengguna')
                      ->orWhereIn('jenis', ['perpanjangan_diajukan']);
                });
            }
        }

        $notifikasi = $query->get();

        // Tandai sudah dibaca untuk scope user ini
        $ids = $notifikasi->pluck('id_notifikasi');
        if ($ids->isNotEmpty() && Schema::hasColumn('notifikasi', 'dibaca')) {
            $queryUpdate = Notifikasi::whereIn('id_notifikasi', $ids);
            $queryUpdate->update(['dibaca' => true]);
        }

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus');
    }

    protected function resolveAuthPenggunaId(): ?int
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        $pengguna = \App\Models\Pengguna::find($user->id);

        if (!$pengguna && $user->email) {
            $pengguna = \App\Models\Pengguna::where('email', $user->email)->first();
        }

        return $pengguna?->id_pengguna;
    }
}
