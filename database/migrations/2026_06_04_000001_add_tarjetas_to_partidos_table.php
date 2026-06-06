<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->unsignedSmallInteger('tarjetas_amarillas_local')->default(0)->after('goles_visitante');
            $table->unsignedSmallInteger('tarjetas_rojas_local')->default(0)->after('tarjetas_amarillas_local');
            $table->unsignedSmallInteger('tarjetas_amarillas_visitante')->default(0)->after('tarjetas_rojas_local');
            $table->unsignedSmallInteger('tarjetas_rojas_visitante')->default(0)->after('tarjetas_amarillas_visitante');
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropColumn([
                'tarjetas_amarillas_local',
                'tarjetas_rojas_local',
                'tarjetas_amarillas_visitante',
                'tarjetas_rojas_visitante',
            ]);
        });
    }
};
