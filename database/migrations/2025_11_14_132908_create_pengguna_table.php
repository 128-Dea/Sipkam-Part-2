<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->increments('id_pengguna');
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('nomor_hp', 20);
            $table->enum('role', ['mahasiswa', 'petugas']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
