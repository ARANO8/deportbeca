<?php

namespace Tests\Feature;

use App\Models\Discipline;
use App\Models\EventoConfiguracion;
use App\Models\Preinscripcion;
use App\Models\PreinscripcionIntegrante;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PreinscripcionLimiteIntegrantesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Igual que el resto de tests del proyecto: corre migraciones via Artisan.
     */
    protected function refreshInMemoryDatabase()
    {
        Artisan::call('migrate', ['--env' => 'testing']);
        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Crea un evento 'libre' (sin facultad/carrera) con una disciplina de voley
     * que solo admite modalidad grupal (rango 6-12).
     *
     * @return array{0: EventoConfiguracion, 1: Discipline}
     */
    private function crearEventoConVoley(array $rangoVoley = []): array
    {
        $evento = EventoConfiguracion::create([
            'tipo_evento' => 'libre',
            'nombre' => 'Evento Test',
            'descripcion' => 'desc',
            'activo' => true,
            'codigo_acceso' => 'TST-AAA111',
            'min_integrantes_grupal' => 4,
            'max_integrantes_grupal' => 8,
        ]);

        $voley = Discipline::create(array_merge([
            'codigo' => 'VOL-TST',
            'nombre' => 'Voleibol Test',
            'status' => 'active',
            'min_integrantes_grupal' => 6,
            'max_integrantes_grupal' => 12,
        ], $rangoVoley));

        $evento->disciplines()->attach($voley->id);

        return [$evento, $voley];
    }

    private function payload(Discipline $voley, string $tipo, int $personas): array
    {
        // persona 1 = capitan/representante; 2..N = integrantes
        $integrantes = [];
        for ($i = 2; $i <= $personas; $i++) {
            $integrantes[$i] = ['nombre' => 'Jugador '.$i, 'ci' => '100'.$i];
        }

        return [
            'tipo_inscripcion' => $tipo,
            'disciplina_id' => $voley->id,
            'representante_nombre' => 'Capitan Uno',
            'representante_ci' => '1000001',
            'representante_email' => 'capitan@test.com',
            'representante_telefono' => '70000000',
            'nombre_equipo' => 'Equipo Test',
            'integrantes' => $integrantes,
        ];
    }

    public function test_rechaza_equipo_con_menos_del_minimo(): void
    {
        [$evento, $voley] = $this->crearEventoConVoley();

        $response = $this->withSession(['evento_activo_id' => $evento->id])
            ->postJson(route('preinscripcion.store'), $this->payload($voley, 'grupal', 2));

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertSame(0, Preinscripcion::count());
    }

    public function test_rechaza_equipo_con_mas_del_maximo(): void
    {
        [$evento, $voley] = $this->crearEventoConVoley();

        $response = $this->withSession(['evento_activo_id' => $evento->id])
            ->postJson(route('preinscripcion.store'), $this->payload($voley, 'grupal', 13));

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertSame(0, Preinscripcion::count());
    }

    public function test_acepta_equipo_dentro_del_rango(): void
    {
        [$evento, $voley] = $this->crearEventoConVoley();

        $response = $this->withSession(['evento_activo_id' => $evento->id])
            ->postJson(route('preinscripcion.store'), $this->payload($voley, 'grupal', 6));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertSame(1, Preinscripcion::count());
        $this->assertSame(6, Preinscripcion::first()->cantidad_integrantes);
        // 1 capitan + 5 integrantes adicionales
        $this->assertSame(6, PreinscripcionIntegrante::count());
    }

    public function test_rechaza_modalidad_no_permitida(): void
    {
        // Voley solo define rango grupal, por lo que no admite individual.
        [$evento, $voley] = $this->crearEventoConVoley();

        $response = $this->withSession(['evento_activo_id' => $evento->id])
            ->postJson(route('preinscripcion.store'), $this->payload($voley, 'individual', 1));

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertSame(0, Preinscripcion::count());
    }
}
