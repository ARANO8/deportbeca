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
        if (!Schema::hasColumn('disciplines', 'ubicacion_mapa')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->text('ubicacion_mapa')->nullable()->after('descripcion');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('disciplines', 'ubicacion_mapa')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->dropColumn('ubicacion_mapa');
            });
        }
    }
};