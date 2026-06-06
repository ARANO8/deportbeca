<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Elimina columnas redundantes de partidos.
 *
 * evento_configuracion_id y disciplina_id ya se obtienen a traves de
 * partido -> serie -> evento/disciplina. Mantenerlas duplicadas permitia que
 * un partido apuntara a un evento distinto al de su serie. La serie es la
 * unica fuente de verdad. El modelo Partido expone accessors que delegan en
 * la serie para compatibilidad con el codigo existente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            // SQLite no soporta DROP FOREIGN KEY; al eliminar la columna
            // reconstruye la tabla y descarta la FK automaticamente.
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['evento_configuracion_id']);
                $table->dropForeign(['disciplina_id']);
            }
            $table->dropColumn(['evento_configuracion_id', 'disciplina_id']);
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->foreignId('evento_configuracion_id')->nullable()->after('serie_id')
                ->constrained('evento_configuraciones');
            $table->foreignId('disciplina_id')->nullable()->after('evento_configuracion_id')
                ->constrained('disciplines');
        });
    }
};
