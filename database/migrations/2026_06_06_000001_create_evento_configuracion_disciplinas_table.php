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
        Schema::create('evento_configuracion_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_configuracion_id')
                ->constrained('evento_configuraciones')
                ->onDelete('cascade');
            $table->foreignId('discipline_id')
                ->constrained('disciplines')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['evento_configuracion_id', 'discipline_id'], 'ec_discipline_unique');
        });

        // 2. Migrar datos existentes del JSON a la tabla junction
        $configuraciones = DB::table('evento_configuraciones')
            ->whereNotNull('disciplinas_ids')
            ->get(['id', 'disciplinas_ids']);

        foreach ($configuraciones as $config) {
            $ids = json_decode($config->disciplinas_ids, true);

            if (! is_array($ids) || empty($ids)) {
                continue;
            }

            foreach ($ids as $disciplineId) {
                $disciplineId = (int) $disciplineId;
                if ($disciplineId <= 0) {
                    continue;
                }

                // Solo insertar si la discipline existe (evitar FK violation)
                $exists = DB::table('disciplines')->where('id', $disciplineId)->exists();
                if ($exists) {
                    DB::table('evento_configuracion_disciplinas')->insertOrIgnore([
                        'evento_configuracion_id' => $config->id,
                        'discipline_id' => $disciplineId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. Eliminar la columna JSON que ya no es necesaria
        Schema::table('evento_configuraciones', function (Blueprint $table) {
            $table->dropColumn('disciplinas_ids');
        });
    }

    public function down(): void
    {
        // Re-agregar columna JSON
        Schema::table('evento_configuraciones', function (Blueprint $table) {
            $table->json('disciplinas_ids')->nullable()->after('codigo_acceso');
        });

        // Restaurar datos agrupados
        $rows = DB::table('evento_configuracion_disciplinas')
            ->select('evento_configuracion_id', DB::raw('GROUP_CONCAT(discipline_id) as ids'))
            ->groupBy('evento_configuracion_id')
            ->get();

        foreach ($rows as $row) {
            $ids = array_map('intval', explode(',', $row->ids));
            DB::table('evento_configuraciones')
                ->where('id', $row->evento_configuracion_id)
                ->update(['disciplinas_ids' => json_encode($ids)]);
        }

        Schema::dropIfExists('evento_configuracion_disciplinas');
    }
};
