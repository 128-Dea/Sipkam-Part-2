<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluhan', function (Blueprint $table) {
            if (!Schema::hasColumn('keluhan', 'created_at')) {
                $table->timestamp('created_at')->useCurrent()->after('handled_at');
            }
            if (!Schema::hasColumn('keluhan', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('keluhan', function (Blueprint $table) {
            if (Schema::hasColumn('keluhan', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('keluhan', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};
