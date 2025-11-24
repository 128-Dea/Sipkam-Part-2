<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah jenis dari ENUM ke VARCHAR agar fleksibel menampung semua tipe notifikasi baru
        DB::statement('ALTER TABLE `notifikasi` MODIFY `jenis` VARCHAR(50) NOT NULL');
    }

    public function down(): void
    {
        // Kembalikan ke ENUM lama jika rollback (waspada: bisa gagal bila sudah ada nilai baru)
        DB::statement("
            ALTER TABLE `notifikasi`
            MODIFY `jenis` ENUM('perpanjangan','denda','serah_terima') NOT NULL
        ");
    }
};
