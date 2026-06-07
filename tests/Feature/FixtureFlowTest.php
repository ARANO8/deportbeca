<?php

namespace Tests\Feature;

use App\Models\Serie;
use App\Services\FixtureGenerationService;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Verifica el flujo central del sistema sobre las tablas normalizadas:
 * generacion de series, asociacion de equipos via la tabla junction
 * (serie_preinscripciones) y creacion de partidos round-robin.
 */
class FixtureFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function refreshInMemoryDatabase()
    {
        Artisan::call('migrate', ['--env' => 'testing']);
        $this->app[ConsoleKernel::class]->setArtisan(null);
    }

    /**
     * @return array{0:int,1:int,2:array<int>}
     */
    private function sembrarEventoConEquipos(int $cantidadEquipos = 4): array
    {
        $eventoId = DB::table('evento_configuraciones')->insertGetId([
            'tipo_evento' => 'intercarreras',
            'nombre' => 'Intercarreras Test',
            'activo' => 1,
            'codigo_acceso' => 'IC-TEST',
            'max_integrantes_grupal' => 8,
            'min_integrantes_grupal' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $disciplinaId = DB::table('disciplines')->insertGetId([
            'codigo' => 'FUT-T',
            'nombre' => 'Futbol Test',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ids = [];
        for ($i = 0; $i < $cantidadEquipos; $i++) {
            $ids[] = DB::table('preinscripciones')->insertGetId([
                'tipo_evento' => 'intercarreras',
                'tipo_inscripcion' => 'grupal',
                'disciplina_id' => $disciplinaId,
                'nombre_equipo' => 'Equipo '.chr(65 + $i),
                'cantidad_integrantes' => 5,
                'representante_nombre' => 'Rep '.$i,
                'representante_ci' => '100'.$i,
                'representante_email' => 'rep'.$i.'@test.com',
                'representante_telefono' => '7000000'.$i,
                'estado' => 'habilitado',
                'codigo_inscripcion' => 'INS-TEST-'.$i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return [$eventoId, $disciplinaId, $ids];
    }

    public function test_genera_serie_con_equipos_en_junction_y_partidos(): void
    {
        [$eventoId, $disciplinaId, $ids] = $this->sembrarEventoConEquipos(4);

        app(FixtureGenerationService::class)->crearSeriesYPartidos(
            $eventoId,
            $disciplinaId,
            $ids,
            1,
            []
        );

        // Se creo exactamente una serie para el evento/disciplina
        $series = Serie::where('evento_configuracion_id', $eventoId)
            ->where('disciplina_id', $disciplinaId)
            ->get();
        $this->assertCount(1, $series, 'Debe crearse una serie');

        $serie = $series->first();

        // Los 4 equipos quedan asociados via la tabla junction normalizada
        $this->assertEquals(4, $serie->preinscripciones()->count(), 'La serie debe tener 4 equipos en la junction');

        // El pivote guarda el orden y es_clasificado por defecto en false
        $primero = $serie->preinscripciones()->first();
        $this->assertNotNull($primero->pivot->orden);
        $this->assertEquals(0, (int) $primero->pivot->es_clasificado);

        // Round-robin de 4 equipos => 6 partidos
        $this->assertEquals(6, $serie->partidos()->count(), 'Round-robin de 4 equipos debe generar 6 partidos');

        // Cada partido referencia equipos reales de la serie
        $idsEnSerie = $serie->preinscripciones()->pluck('preinscripciones.id')->all();
        foreach ($serie->partidos as $partido) {
            $this->assertContains($partido->equipo_local_id, $idsEnSerie);
            $this->assertContains($partido->equipo_visitante_id, $idsEnSerie);
        }
    }

    public function test_accesor_equipos_apunta_a_la_relacion_normalizada(): void
    {
        [$eventoId, $disciplinaId, $ids] = $this->sembrarEventoConEquipos(4);

        app(FixtureGenerationService::class)->crearSeriesYPartidos($eventoId, $disciplinaId, $ids, 1, []);

        $serie = Serie::where('evento_configuracion_id', $eventoId)->first();

        // El accesor de compatibilidad $serie->equipos refleja la relacion
        $this->assertCount(4, $serie->equipos);
    }
}
