<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\Peminjaman;
use App\Models\Qr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Peminjaman::with(['barang', 'pengguna', 'qr', 'perpanjangan', 'keluhan'])
            ->orderByDesc('waktu_awal');

        if ($user && $user->role === 'mahasiswa') {
            $penggunaId = $this->resolvePenggunaId($user);
            if ($penggunaId) {
                $query->where('id_pengguna', $penggunaId)
                    ->where('status', '!=', 'selesai');
            }
        }

        $peminjaman = $query->get();

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function booking()
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'petugas', 403);

        $booking = Peminjaman::with(['barang', 'pengguna', 'qr'])
            ->whereIn('status', ['booking', 'ditolak'])
            ->orderByDesc('waktu_awal')
            ->get();

        return view('peminjaman.booking', ['booking' => $booking]);
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'mahasiswa', 403);

        $prefillBarangId = $request->query('barang_id');

        $barang = Barang::with('kategori')
            ->where(function ($q) {
                $q->whereNull('stok')->orWhere('stok', '>', 0);
            })
            ->where('status', 'tersedia')
            ->get();

        return view('peminjaman.create', [
            'barang' => $barang,
            'prefillBarangId' => $prefillBarangId,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'mahasiswa', 403);

        $data = $request->validate([
            'id_barang'   => 'required|exists:barang,id_barang',
            'waktu_awal'  => 'required|date',
            'waktu_akhir' => 'required|date|after:waktu_awal',
            'alasan'      => 'nullable|string',
        ]);

        $waktuAwal = Carbon::parse($data['waktu_awal']);
        $waktuAkhir = Carbon::parse($data['waktu_akhir']);

        if ($waktuAwal->diffInMinutes($waktuAkhir) > 5 * 60) {
            return back()
                ->withErrors(['waktu_akhir' => 'Durasi peminjaman maksimal 5 jam. Ajukan perpanjangan jika perlu lebih lama.'])
                ->withInput();
        }

        $pengguna = $this->ensurePengguna($user);

        $peminjaman = Peminjaman::create([
            'id_pengguna' => $pengguna?->id_pengguna,
            'id_barang'   => $data['id_barang'],
            'waktu_awal'  => $data['waktu_awal'],
            'waktu_akhir' => $data['waktu_akhir'],
            'alasan'      => $data['alasan'] ?? '',
            'status'      => 'booking',
        ]);

        $this->updateBarangSetelahPinjam($peminjaman->barang);

        Qr::create([
            'qr_code'         => $this->generateQrCode($peminjaman->id_peminjaman),
            'jenis_transaksi' => 'peminjaman',
            'id_peminjaman'   => $peminjaman->id_peminjaman,
            'is_active'       => true,
        ]);

        return redirect()
            ->route('mahasiswa.peminjaman.show', $peminjaman->id_peminjaman)
            ->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function show(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        if ($user && $user->role === 'mahasiswa' && $peminjaman->id_pengguna !== $this->resolvePenggunaId($user)) {
            abort(403);
        }

        $peminjaman->load(['barang', 'pengguna', 'qr', 'keluhan', 'perpanjangan']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'petugas', 403);

        $peminjaman->delete();

        return redirect()
            ->route('petugas.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    /**
     * Aktifkan peminjaman dari hasil scan QR (petugas).
     * Mengubah status booking => berlangsung (aktif).
     */
    public function activateFromScan(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'petugas', 403);

        $data = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $kodeTransaksi = $data['qr_code'];
        $decoded = json_decode($kodeTransaksi, true);
        if (is_array($decoded) && isset($decoded['kode_transaksi'])) {
            $kodeTransaksi = $decoded['kode_transaksi'];
        }

        $qr = Qr::with('peminjaman')
            ->where('qr_code', $kodeTransaksi)
            ->where('jenis_transaksi', 'peminjaman')
            ->where('is_active', true)
            ->first();

        if (!$qr || !$qr->peminjaman) {
            return response()->json(['message' => 'QR tidak valid atau peminjaman tidak ditemukan'], 422);
        }

        $peminjaman = $qr->peminjaman;

        if ($peminjaman->status === 'booking') {
            $peminjaman->update(['status' => 'berlangsung']);
        }

        return response()->json([
            'message' => 'Peminjaman diaktifkan',
            'status'  => $peminjaman->status,
        ]);
    }

    protected function generateQrCode(int $id): string
    {
        return 'PINJ-' . $id . '-' . Str::upper(Str::random(6));
    }

    protected function updateBarangSetelahPinjam(?Barang $barang): void
    {
        if (!$barang) {
            return;
        }

        // Kurangi stok jika kolom tersedia, lalu perbarui status sederhana.
        if (!is_null($barang->stok)) {
            $barang->decrement('stok');
            $barang->refresh();

            if (in_array($barang->status, ['tersedia', 'dipinjam'])) {
                $barang->update([
                    'status' => $barang->stok > 0 ? 'tersedia' : 'dipinjam',
                ]);
            }
        } elseif (in_array($barang->status, ['tersedia', 'dipinjam'])) {
            $barang->update(['status' => 'dipinjam']);
        }
    }

    protected function ensurePengguna($user): ?Pengguna
    {
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

        return $pengguna;
    }

    protected function resolvePenggunaId($user): ?int
    {
        return $this->ensurePengguna($user)?->id_pengguna;
    }
}
