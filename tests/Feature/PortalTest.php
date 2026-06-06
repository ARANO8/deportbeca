<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bypass PendingCommand/Mockery by running migrations via Artisan facade directly.
     * Class method takes precedence over the RefreshDatabase trait method.
     */
    protected function refreshInMemoryDatabase()
    {
        Artisan::call('migrate', ['--env' => 'testing']);
        $this->app[Kernel::class]->setArtisan(null);
    }

    // ─── Fixtures de datos ────────────────────────────────────────────────────

    private function crearDisciplina(): int
    {
        return DB::table('disciplines')->insertGetId([
            'codigo' => 'FUT-TEST',
            'nombre' => 'Futbol Test',
            'descripcion' => 'Disciplina para tests',
            'parent_id' => null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function crearEvento(): int
    {
        return DB::table('evento_configuraciones')->insertGetId([
            'nombre' => 'Evento Test',
            'tipo_evento' => 'libre',
            'activo' => 1,
            'codigo_acceso' => 'TEST001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function crearSerie(int $eventoId, int $disciplinaId): int
    {
        return DB::table('series')->insertGetId([
            'evento_configuracion_id' => $eventoId,
            'disciplina_id' => $disciplinaId,
            'nombre_serie' => 'Serie A',
            'numero_serie' => 1,
            'cantidad_equipos' => 0,
            'estado' => 'en_curso',
            'tipo_competencia' => 'todos_contra_todos',
            'cuantos_clasifican' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ─── Portal index ─────────────────────────────────────────────────────────

    public function test_portal_index_accesible_sin_autenticacion(): void
    {
        $response = $this->get(route('portal.index'));

        $response->assertStatus(200);
    }

    public function test_portal_index_muestra_eventos_activos(): void
    {
        $this->crearEvento();

        $response = $this->get(route('portal.index'));

        $response->assertStatus(200);
        $response->assertSee('Evento Test');
    }

    // ─── Portal evento ────────────────────────────────────────────────────────

    public function test_portal_evento_muestra_detalle_de_evento_existente(): void
    {
        $eventoId = $this->crearEvento();

        $response = $this->get(route('portal.evento', $eventoId));

        $response->assertStatus(200);
        $response->assertSee('Evento Test');
    }

    public function test_portal_evento_retorna_404_para_id_inexistente(): void
    {
        $response = $this->get(route('portal.evento', 99999));

        $response->assertStatus(404);
    }

    // ─── Portal serie ─────────────────────────────────────────────────────────

    public function test_portal_serie_muestra_tabla_de_posiciones(): void
    {
        $disciplinaId = $this->crearDisciplina();
        $eventoId = $this->crearEvento();
        $serieId = $this->crearSerie($eventoId, $disciplinaId);

        $response = $this->get(route('portal.serie', $serieId));

        $response->assertStatus(200);
        $response->assertSee('Serie A');
    }

    public function test_portal_serie_retorna_404_para_id_inexistente(): void
    {
        $response = $this->get(route('portal.serie', 99999));

        $response->assertStatus(404);
    }

    // ─── Portal fixture ───────────────────────────────────────────────────────

    public function test_portal_fixture_muestra_partidos_del_evento(): void
    {
        $eventoId = $this->crearEvento();

        $response = $this->get(route('portal.fixture', $eventoId));

        $response->assertStatus(200);
        $response->assertSee('Evento Test');
    }

    public function test_portal_fixture_retorna_404_para_evento_inexistente(): void
    {
        $response = $this->get(route('portal.fixture', 99999));

        $response->assertStatus(404);
    }

    // ─── Acceso sin login ─────────────────────────────────────────────────────

    public function test_portal_no_redirige_al_login(): void
    {
        $eventoId = $this->crearEvento();
        $disciplinaId = $this->crearDisciplina();
        $serieId = $this->crearSerie($eventoId, $disciplinaId);

        foreach ([
            route('portal.index'),
            route('portal.evento', $eventoId),
            route('portal.serie', $serieId),
            route('portal.fixture', $eventoId),
        ] as $url) {
            $response = $this->get($url);
            // assertStatus(200) ya garantiza que no hay redireccion (302) al login
            $response->assertStatus(200);
        }
    }
}
