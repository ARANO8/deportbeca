<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Garantiza una unica configuracion de fixture por evento + disciplina.
 *
 * La logica de negocio asume exactamente una FixtureConfiguracion por
 * combinacion evento/disciplina. Sin este UNIQUE nada impedia insertar
 * duplicados que romperian el calculo del fixture.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixture_configuraciones', function (Blueprint $table) {
            $table->unique(['evento_configuracion_id', 'disciplina_id'], 'fixture_evento_disciplina_unique');
        });
    }

    public function down(): void
    {
        Schema::table('fixture_configuraciones', function (Blueprint $table) {
            $table->dropUnique('fixture_evento_disciplina_unique');
        });
    }
};
