<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->increments('id_peminjaman');
            $table->unsignedInteger('id_pengguna')->index();
            $table->unsignedInteger('id_barang')->index();
            $table->dateTime('waktu_awal');
            $table->dateTime('waktu_akhir');
            $table->text('alasan');

            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        // Catatan: Trigger MySQL yang berhubungan dengan tabel peminjaman tidak dibuat otomatis oleh migration Laravel ini.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
