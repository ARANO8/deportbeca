<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega índices de rendimiento en columnas de búsqueda frecuente.
 * Corrige DAT-06 del informe de auditoría.
 */
return new class extends Migration
{
    public function up(): void
    {
        // preinscripciones: filtros de ArchivadorController::index()
        Schema::table('preinscripciones', function (Blueprint $table) {
            $table->index(['tipo_evento', 'estado'], 'idx_preinsc_evento_estado');
            $table->index(['disciplina_id', 'estado'], 'idx_preinsc_disciplina_estado');
        });

        // series: filtros de FixtureController
        Schema::table('series', function (Blueprint $table) {
            $table->index(['evento_configuracion_id', 'disciplina_id', 'estado'], 'idx_series_evento_disc_estado');
        });

        // partidos: conteo de pendientes en generarSiguienteFase()
        Schema::table('partidos', function (Blueprint $table) {
            $table->index(['serie_id', 'estado'], 'idx_partidos_serie_estado');
        });

        // estadisticas: orden de tabla de posiciones (getTablaPosicionesAttribute)
        Schema::table('estadisticas', function (Blueprint $table) {
            $table->index(['serie_id', 'pts', 'dg', 'gf'], 'idx_estadisticas_posiciones');
        });
    }

    public function down(): void
    {
        Schema::table('preinscripciones', function (Blueprint $table) {
            $table->dropIndex('idx_preinsc_evento_estado');
            $table->dropIndex('idx_preinsc_disciplina_estado');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropIndex('idx_series_evento_disc_estado');
        });

        Schema::table('partidos', function (Blueprint $table) {
            $table->dropIndex('idx_partidos_serie_estado');
        });

        Schema::table('estadisticas', function (Blueprint $table) {
            $table->dropIndex('idx_estadisticas_posiciones');
        });
    }
};
