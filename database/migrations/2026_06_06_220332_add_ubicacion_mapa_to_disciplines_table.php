<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< Updated upstream
        if (! Schema::hasColumn('disciplines', 'ubicacion_mapa')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->text('ubicacion_mapa')->nullable()->after('descripcion');
=======
        if (!Schema::hasColumn('lugares', 'ubicacion_mapa')) {
            Schema::table('lugares', function (Blueprint $table) {
                $table->text('ubicacion_mapa')->nullable()->after('direccion');
>>>>>>> Stashed changes
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lugares', 'ubicacion_mapa')) {
            Schema::table('lugares', function (Blueprint $table) {
                $table->dropColumn('ubicacion_mapa');
            });
        }
    }
};
