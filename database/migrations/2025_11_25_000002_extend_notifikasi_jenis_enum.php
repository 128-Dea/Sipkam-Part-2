<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Perluas enum kolom jenis agar memuat semua tipe notifikasi yang dipakai aplikasi.
        DB::statement("
            ALTER TABLE `notifikasi`
            MODIFY `jenis` ENUM(
                'perpanjangan',
                'denda',
                'serah_terima',
                'perpanjangan_diajukan',
                'denda_baru',
                'peminjaman_akan_habis',
                'peminjaman_terlambat',
                'peminjaman_terlambat_mahasiswa'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        // Kembalikan ke enum awal (akan gagal jika ada data dengan jenis baru).
        DB::statement("
            ALTER TABLE `notifikasi`
            MODIFY `jenis` ENUM('perpanjangan','denda','serah_terima') NOT NULL
        ");
    }
};
