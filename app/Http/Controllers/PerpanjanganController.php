<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Perpanjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PerpanjanganController extends Controller
{
    public function index()
    {
        $perpanjangan = Perpanjangan::with('peminjaman.pengguna')->orderByDesc('waktu_pengajuan')->get();

        return view('perpanjangan.index', compact('perpanjangan'));
    }

    public function create()
    {
        $authPenggunaId = $this->resolveAuthPenggunaId();
        $prefillId = request('id_peminjaman');

        $peminjaman = Peminjaman::with(['pengguna', 'barang'])
            ->where('id_pengguna', $authPenggunaId)
            ->where('status', 'berlangsung')
            ->whereDoesntHave('pengembalian')
            ->get();

        return view('perpanjangan.create', compact('peminjaman', 'prefillId'));
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
        if ($peminjaman->id_pengguna !== $this->resolveAuthPenggunaId()) {
            return back()->withErrors(['id_peminjaman' => 'Anda tidak memiliki akses untuk memperpanjang peminjaman ini.']);
        }

        // Hanya peminjaman yang sudah berjalan yang boleh diperpanjang
        if ($peminjaman->status !== 'berlangsung') {
            return back()->withErrors(['id_peminjaman' => 'Perpanjangan hanya bisa untuk peminjaman yang sedang berlangsung.']);
        }

        $requestedEnd = Carbon::parse($data['waktu_perpanjangan']);
        $currentEnd   = Carbon::parse($peminjaman->waktu_akhir);

        if ($requestedEnd->lessThanOrEqualTo($currentEnd)) {
            return back()->withErrors(['waktu_perpanjangan' => 'Waktu perpanjangan harus lebih lama dari waktu kembali saat ini.'])->withInput();
        }

        if ($currentEnd->diffInMinutes($requestedEnd) > 5 * 60) {
            return back()->withErrors(['waktu_perpanjangan' => 'Perpanjangan maksimal 5 jam per pengajuan.'])->withInput();
        }

        // Cek konflik jadwal pada barang yang sama selama periode perpanjangan
        $conflictExists = Peminjaman::where('id_barang', $peminjaman->id_barang)
            ->where('id_peminjaman', '!=', $peminjaman->id_peminjaman)
            ->whereIn('status', ['berlangsung', 'booking'])
            ->where('waktu_awal', '<', $requestedEnd)
            ->where('waktu_akhir', '>', $currentEnd)
            ->exists();

        $data['status_persetujuan'] = $conflictExists ? 'ditolak' : 'disetujui';

        DB::transaction(function () use ($data, $peminjaman, $requestedEnd, $conflictExists) {
            Perpanjangan::create($data);

            // Jika tidak ada konflik, langsung setujui dan perbarui akhir peminjaman
            if (!$conflictExists) {
                $peminjaman->update([
                    'waktu_akhir' => $requestedEnd,
                    'status' => $peminjaman->status, // tidak mengubah status berjalan
                ]);
            }
        });

        // Notifikasi petugas tentang pengajuan perpanjangan (gunakan id_pengguna peminjam agar tidak NULL)
        \App\Models\Notifikasi::create([
            'id_barang'   => $peminjaman->id_barang,
            'id_pengguna' => null, // target petugas
            'jenis'       => 'perpanjangan_diajukan',
            'pesan'       => sprintf(
                'Perpanjangan diajukan oleh %s untuk %s (PINJ#%d) sampai %s. Status awal: %s.',
                $peminjaman->pengguna->nama ?? 'Mahasiswa',
                $peminjaman->barang->nama_barang ?? 'Barang',
                $peminjaman->id_peminjaman,
                $requestedEnd->translatedFormat('d M Y H:i'),
                $conflictExists ? 'bentrok (otomatis ditolak)' : 'langsung disetujui'
            ),
        ]);

        $message = $conflictExists
            ? 'Perpanjangan ditolak otomatis karena jadwal bentrok dengan peminjaman lain.'
            : 'Perpanjangan disetujui otomatis karena jadwal kosong.';

        return redirect()->route('mahasiswa.perpanjangan.index')->with('success', $message);
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

        if (!$pengguna) {
            $nomorHp = $user->phone ?? $user->nomor_hp ?? '0';
            $pengguna = new \App\Models\Pengguna();
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
