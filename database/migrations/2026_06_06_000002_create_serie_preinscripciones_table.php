<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear tabla junction con FKs reales
        Schema::create('serie_preinscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')
                ->constrained('series')
                ->onDelete('cascade');
            $table->foreignId('preinscripcion_id')
                ->constrained('preinscripciones')
                ->onDelete('cascade');
            // Reemplaza clasificados_ids: true = este equipo clasifico a la siguiente fase
            $table->boolean('es_clasificado')->default(false);
            // Orden de asignacion dentro de la serie (para reproducir el sorteo)
            $table->unsignedSmallInteger('orden')->nullable();
            $table->timestamps();

            $table->unique(['serie_id', 'preinscripcion_id'], 'serie_preinscripcion_unique');
        });

        // 2. Migrar datos de equipos_ids y clasificados_ids
        $series = DB::table('series')
            ->whereNotNull('equipos_ids')
            ->get(['id', 'equipos_ids', 'clasificados_ids']);

        foreach ($series as $serie) {
            $equiposIds = json_decode($serie->equipos_ids, true) ?? [];
            $clasificadosIds = json_decode($serie->clasificados_ids ?? '[]', true) ?? [];

            if (! is_array($equiposIds)) {
                continue;
            }

            // Usar set para lookup O(1)
            $clasificadosSet = array_flip(array_filter($clasificadosIds, 'is_numeric'));

            foreach ($equiposIds as $orden => $preinscripcionId) {
                $preinscripcionId = (int) $preinscripcionId;
                if ($preinscripcionId <= 0) {
                    continue;
                }

                // Solo insertar si la preinscripcion existe
                $exists = DB::table('preinscripciones')->where('id', $preinscripcionId)->exists();
                if (! $exists) {
                    continue;
                }

                DB::table('serie_preinscripciones')->insertOrIgnore([
                    'serie_id' => $serie->id,
                    'preinscripcion_id' => $preinscripcionId,
                    'es_clasificado' => isset($clasificadosSet[$preinscripcionId]) ? 1 : 0,
                    'orden' => (int) $orden,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Eliminar columnas JSON
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn(['equipos_ids', 'clasificados_ids']);
        });
    }

    public function down(): void
    {
        // Re-agregar columnas JSON
        Schema::table('series', function (Blueprint $table) {
            $table->json('equipos_ids')->nullable()->after('cantidad_equipos');
            $table->json('clasificados_ids')->nullable()->after('cuantos_clasifican');
        });

        // Restaurar datos agrupados ordenados por orden
        $rows = DB::table('serie_preinscripciones')
            ->orderBy('serie_id')
            ->orderBy('orden')
            ->orderBy('id')
            ->get(['serie_id', 'preinscripcion_id', 'es_clasificado']);

        $equipos = [];
        $clasificados = [];

        foreach ($rows as $row) {
            $equipos[$row->serie_id][] = (int) $row->preinscripcion_id;
            if ($row->es_clasificado) {
                $clasificados[$row->serie_id][] = (int) $row->preinscripcion_id;
            }
        }

        foreach ($equipos as $serieId => $ids) {
            DB::table('series')->where('id', $serieId)->update([
                'equipos_ids' => json_encode(array_values($ids)),
                'clasificados_ids' => json_encode(array_values($clasificados[$serieId] ?? [])),
            ]);
        }

        Schema::dropIfExists('serie_preinscripciones');
    }
};
