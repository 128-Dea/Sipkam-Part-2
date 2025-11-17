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
        Schema::create('service', function (Blueprint $table) {
            $table->increments('id_service');
            $table->unsignedInteger('id_keluhan');
            $table->enum('status', ['mengantri', 'diperbaiki', 'selesai'])->default('mengantri');

            $table->foreign('id_keluhan')
                ->references('id_keluhan')
                ->on('keluhan')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
