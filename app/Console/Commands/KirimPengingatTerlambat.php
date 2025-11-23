<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class KirimPengingatTerlambat extends Command
{
    protected $signature = 'peminjaman:notify-overdue';

    protected $description = 'Notifikasi petugas jika ada peminjaman yang sudah melewati batas waktu';

    public function handle(): int
    {
        $now = Carbon::now();

        $peminjaman = Peminjaman::with(['pengguna', 'barang'])
            ->where('status', 'berlangsung')
            ->where('waktu_akhir', '<', $now)
            ->get();

        $count = 0;

        foreach ($peminjaman as $item) {
            $marker = 'PINJ#' . $item->id_peminjaman;
            $sudahAda = Notifikasi::where('jenis', 'peminjaman_terlambat')
                ->where('pesan', 'like', '%' . $marker . '%')
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Notifikasi::create([
                'id_barang'   => $item->id_barang,
                'id_pengguna' => null, // ditujukan ke petugas
                'jenis'       => 'peminjaman_terlambat',
                'pesan'       => sprintf(
                    'Peminjaman terlambat: %s (PINJ#%d) oleh %s. Batas: %s',
                    $item->barang->nama_barang ?? 'Barang',
                    $item->id_peminjaman,
                    $item->pengguna->nama ?? 'Mahasiswa',
                    Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i')
                ),
            ]);

            // Notifikasi ke mahasiswa yang bersangkutan
            if ($item->pengguna) {
                $sudahAdaMhs = Notifikasi::where('jenis', 'peminjaman_terlambat_mahasiswa')
                    ->where('id_pengguna', $item->pengguna->id_pengguna)
                    ->where('pesan', 'like', '%' . $marker . '%')
                    ->exists();

                if (!$sudahAdaMhs) {
                    Notifikasi::create([
                        'id_barang'   => $item->id_barang,
                        'id_pengguna' => $item->pengguna->id_pengguna,
                        'jenis'       => 'peminjaman_terlambat_mahasiswa',
                        'pesan'       => sprintf(
                            'Anda terlambat mengembalikan %s (PINJ#%d). Segera kembalikan untuk menghindari denda bertambah.',
                            $item->barang->nama_barang ?? 'Barang',
                            $item->id_peminjaman
                        ),
                    ]);
                }
            }

            $count++;
        }

        $this->info("Notifikasi terlambat: {$count}");

        return Command::SUCCESS;
    }
}
