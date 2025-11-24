<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE peminjaman
            MODIFY status ENUM('booking','berlangsung','selesai','ditolak','dibatalkan')
            NOT NULL DEFAULT 'booking'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE peminjaman
            SET status = 'berlangsung'
            WHERE status IN ('booking', 'ditolak')
        ");

        DB::statement("
            ALTER TABLE peminjaman
            MODIFY status ENUM('berlangsung','selesai','dibatalkan')
            NOT NULL DEFAULT 'berlangsung'
        ");
    }
};
