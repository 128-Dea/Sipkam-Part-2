<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class KirimPengingatPeminjaman extends Command
{
    protected $signature = 'peminjaman:reminder-expiry';

    protected $description = 'Kirim notifikasi ke mahasiswa 15 menit sebelum waktu peminjaman habis';

    public function handle(): int
    {
        $now = Carbon::now();
        $limit = $now->copy()->addMinutes(15);

        $peminjaman = Peminjaman::with(['pengguna', 'barang'])
            ->whereIn('status', ['berlangsung'])
            ->whereBetween('waktu_akhir', [$now, $limit])
            ->get();

        $count = 0;

        foreach ($peminjaman as $item) {
            if (!$item->pengguna) {
                continue;
            }

            $marker = 'PINJ#' . $item->id_peminjaman;
            $sudahAda = Notifikasi::where('jenis', 'peminjaman_akan_habis')
                ->where('id_pengguna', $item->pengguna->id_pengguna)
                ->where('pesan', 'like', '%' . $marker . '%')
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Notifikasi::create([
                'id_barang'   => $item->id_barang,
                'id_pengguna' => $item->pengguna->id_pengguna,
                'jenis'       => 'peminjaman_akan_habis',
                'pesan'       => sprintf(
                    '%s (PINJ#%d) akan berakhir %s. Segera kembalikan atau ajukan perpanjangan.',
                    $item->barang->nama_barang ?? 'Peminjaman',
                    $item->id_peminjaman,
                    Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i')
                ),
            ]);

            $count++;
        }

        $this->info("Notifikasi terkirim: {$count}");

        return Command::SUCCESS;
    }
}
