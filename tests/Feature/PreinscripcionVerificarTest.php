<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PreinscripcionVerificarTest extends TestCase
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

    private const CODIGO_VALIDO = 'INS-TESTABCD';

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearInscripcionDeEjemplo();
    }

    // ─── Fixtures de datos ────────────────────────────────────────────────────

    private function crearInscripcionDeEjemplo(): void
    {
        $disciplinaId = DB::table('disciplines')->insertGetId([
            'codigo' => 'FUT-VER',
            'nombre' => 'Futbol Verificar',
            'descripcion' => 'Para test de verificacion',
            'parent_id' => null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('preinscripciones')->insert([
            'tipo_evento' => 'libre',
            'tipo_inscripcion' => 'grupal',
            'disciplina_id' => $disciplinaId,
            'nombre_equipo' => 'Equipo Rojo',
            'representante_nombre' => 'Juan Perez',
            'representante_ci' => '1234567',
            'representante_email' => 'juan@test.com',
            'representante_telefono' => '70000000',
            'estado' => 'habilitado',
            'codigo_inscripcion' => self::CODIGO_VALIDO,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ─── Formulario sin codigo ────────────────────────────────────────────────

    public function test_formulario_verificar_sin_codigo_retorna_200(): void
    {
        $response = $this->get(route('preinscripcion.verificar.form'));

        $response->assertStatus(200);
    }

    public function test_formulario_verificar_sin_codigo_no_muestra_datos_de_inscripcion(): void
    {
        $response = $this->get(route('preinscripcion.verificar.form'));

        $response->assertStatus(200);
        // La pagina del formulario no debe mostrar datos de ninguna inscripcion concreta.
        // Se comprueba que el nombre del equipo y el codigo del fixture no aparecen.
        $response->assertDontSee('Equipo Rojo');
        $response->assertDontSee(self::CODIGO_VALIDO);
    }

    // ─── Verificacion con codigo ──────────────────────────────────────────────

    public function test_verificar_codigo_valido_retorna_vista_con_inscripcion(): void
    {
        $response = $this->get(route('preinscripcion.verificar', self::CODIGO_VALIDO));

        $response->assertStatus(200);
        $response->assertSee(self::CODIGO_VALIDO);
        $response->assertSee('Equipo Rojo');
    }

    public function test_verificar_codigo_valido_muestra_estado_habilitado(): void
    {
        $response = $this->get(route('preinscripcion.verificar', self::CODIGO_VALIDO));

        $response->assertStatus(200);
        $response->assertSee('habilitado');
    }

    public function test_verificar_codigo_inexistente_retorna_vista_sin_datos(): void
    {
        $response = $this->get(route('preinscripcion.verificar', 'INS-NOEXISTE'));

        // El controlador devuelve la vista sin lanzar 404 (inscripcion = null)
        $response->assertStatus(200);
        $response->assertDontSee('Equipo Rojo');
    }

    public function test_verificar_codigo_valido_retorna_json_cuando_se_pide(): void
    {
        $response = $this->getJson(route('preinscripcion.verificar', self::CODIGO_VALIDO));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_verificar_codigo_inexistente_retorna_json_con_error(): void
    {
        $response = $this->getJson(route('preinscripcion.verificar', 'INS-NOEXISTE'));

        $response->assertStatus(200);
        $response->assertJson(['success' => false]);
    }

    public function test_verificar_preinscripcion_observada_muestra_estado(): void
    {
        DB::table('preinscripciones')
            ->where('codigo_inscripcion', self::CODIGO_VALIDO)
            ->update(['estado' => 'observado', 'observaciones' => 'Falta documento CI']);

        $response = $this->get(route('preinscripcion.verificar', self::CODIGO_VALIDO));

        $response->assertStatus(200);
        $response->assertSee('observado');
    }
}
