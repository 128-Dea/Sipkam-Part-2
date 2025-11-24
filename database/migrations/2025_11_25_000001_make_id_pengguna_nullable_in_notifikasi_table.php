<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom agar bisa null ketika notifikasi ditujukan ke petugas
        DB::statement('ALTER TABLE `notifikasi` MODIFY `id_pengguna` INT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Kembalikan ke NOT NULL jika perlu rollback
        DB::statement('ALTER TABLE `notifikasi` MODIFY `id_pengguna` INT UNSIGNED NOT NULL');
    }
};
