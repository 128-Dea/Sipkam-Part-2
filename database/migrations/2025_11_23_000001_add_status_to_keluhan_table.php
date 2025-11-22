<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluhan', function (Blueprint $table) {
            $table->enum('status', ['pending', 'ditangani', 'selesai'])
                ->default('pending')
                ->after('keluhan');
            $table->text('tindak_lanjut')->nullable()->after('status');
            $table->dateTime('handled_at')->nullable()->after('tindak_lanjut');
        });
    }

    public function down(): void
    {
        Schema::table('keluhan', function (Blueprint $table) {
            $table->dropColumn(['status', 'tindak_lanjut', 'handled_at']);
        });
    }
};
