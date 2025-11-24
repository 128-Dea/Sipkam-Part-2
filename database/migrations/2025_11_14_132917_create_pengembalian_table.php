<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->increments('id_pengembalian');
            $table->unsignedInteger('id_peminjaman')->index();
            $table->dateTime('waktu_pengembalian');
            $table->text('catatan');

            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
