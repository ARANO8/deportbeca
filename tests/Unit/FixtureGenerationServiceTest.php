<?php

namespace Tests\Unit;

use App\Models\EventoConfiguracion;
use App\Services\FixtureGenerationService;
use Tests\TestCase;

class FixtureGenerationServiceTest extends TestCase
{
    private FixtureGenerationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FixtureGenerationService;
    }

    // ─── getNombreFase ────────────────────────────────────────────────────────

    public function test_nombre_fase_final_para_1_y_2_participantes(): void
    {
        $this->assertSame('Final', $this->service->getNombreFase(1));
        $this->assertSame('Final', $this->service->getNombreFase(2));
    }

    public function test_nombre_fase_semifinal_para_3_y_4_participantes(): void
    {
        $this->assertSame('Semifinal', $this->service->getNombreFase(3));
        $this->assertSame('Semifinal', $this->service->getNombreFase(4));
    }

    public function test_nombre_fase_cuartos_para_5_hasta_8_participantes(): void
    {
        $this->assertSame('Cuartos de Final', $this->service->getNombreFase(5));
        $this->assertSame('Cuartos de Final', $this->service->getNombreFase(8));
    }

    public function test_nombre_fase_octavos_para_9_hasta_16_participantes(): void
    {
        $this->assertSame('Octavos de Final', $this->service->getNombreFase(9));
        $this->assertSame('Octavos de Final', $this->service->getNombreFase(16));
    }

    public function test_nombre_fase_eliminatoria_para_mas_de_16(): void
    {
        $this->assertSame('Fase Eliminatoria', $this->service->getNombreFase(17));
        $this->assertSame('Fase Eliminatoria', $this->service->getNombreFase(32));
    }

    // ─── distribuirEquiposEnSeries ────────────────────────────────────────────

    public function test_distribuir_divide_equipos_equitativamente(): void
    {
        $resultado = $this->service->distribuirEquiposEnSeries(
            $this->makeParticipants(6),
            2
        );

        $this->assertCount(2, $resultado);
        $this->assertSame(6, array_sum($resultado));
        foreach ($resultado as $count) {
            $this->assertSame(3, $count);
        }
    }

    public function test_distribuir_maneja_resto_correctamente(): void
    {
        $resultado = $this->service->distribuirEquiposEnSeries(
            $this->makeParticipants(7),
            2
        );

        $this->assertSame(7, array_sum($resultado));

        $sorted = $resultado;
        sort($sorted);
        $this->assertSame([3, 4], array_values($sorted));
    }

    public function test_distribuir_respeta_override_por_serie(): void
    {
        $resultado = $this->service->distribuirEquiposEnSeries(
            $this->makeParticipants(8),
            2,
            [0 => 5, 1 => 3]
        );

        $this->assertSame(5, $resultado[0]);
        $this->assertSame(3, $resultado[1]);
    }

    public function test_distribuir_retorna_cantidad_correcta_de_series(): void
    {
        $resultado = $this->service->distribuirEquiposEnSeries(
            $this->makeParticipants(12),
            4
        );

        $this->assertCount(4, $resultado);
        $this->assertSame(12, array_sum($resultado));
    }

    // ─── generarPartidosRoundRobin ────────────────────────────────────────────

    public function test_round_robin_n4_genera_6_partidos(): void
    {
        $partidos = $this->service->generarPartidosRoundRobin(
            $this->makeParticipants(4),
            $this->eventoLibre()
        );

        // N=4 equipos → N*(N-1)/2 = 6 partidos
        $this->assertCount(6, $partidos);
    }

    public function test_round_robin_n2_genera_1_partido_en_jornada_1(): void
    {
        $partidos = $this->service->generarPartidosRoundRobin(
            $this->makeParticipants(2),
            $this->eventoLibre()
        );

        $this->assertCount(1, $partidos);
        $this->assertSame(1, $partidos[0]['jornada']);
    }

    public function test_round_robin_n1_retorna_vacio(): void
    {
        $partidos = $this->service->generarPartidosRoundRobin(
            $this->makeParticipants(1),
            $this->eventoLibre()
        );

        $this->assertEmpty($partidos);
    }

    public function test_round_robin_partidos_tienen_estructura_correcta(): void
    {
        $partidos = $this->service->generarPartidosRoundRobin(
            $this->makeParticipants(4),
            $this->eventoLibre()
        );

        foreach ($partidos as $partido) {
            $this->assertArrayHasKey('local_id', $partido);
            $this->assertArrayHasKey('visitante_id', $partido);
            $this->assertArrayHasKey('jornada', $partido);
            $this->assertNotEquals($partido['local_id'], $partido['visitante_id']);
        }
    }

    public function test_round_robin_n4_produce_exactamente_3_jornadas(): void
    {
        $partidos = $this->service->generarPartidosRoundRobin(
            $this->makeParticipants(4),
            $this->eventoLibre()
        );

        $jornadas = array_unique(array_column($partidos, 'jornada'));

        $this->assertCount(3, $jornadas);
    }

    public function test_round_robin_n3_impar_maneja_bye_correctamente(): void
    {
        $partidos = $this->service->generarPartidosRoundRobin(
            $this->makeParticipants(3),
            $this->eventoLibre()
        );

        // N=3 → se agrega un bye → N=4 → 3 rondas con 1 partido real c/u = máximo 3
        $this->assertGreaterThan(0, count($partidos));
        $this->assertLessThanOrEqual(3, count($partidos));
    }

    public function test_round_robin_intercarreras_omite_partidos_de_misma_carrera(): void
    {
        $equipos = [
            ['id' => 1, 'carrera_id' => 10, 'tipo_inscripcion' => 'grupal'],
            ['id' => 2, 'carrera_id' => 10, 'tipo_inscripcion' => 'grupal'],
            ['id' => 3, 'carrera_id' => 20, 'tipo_inscripcion' => 'grupal'],
        ];

        $partidos = $this->service->generarPartidosRoundRobin(
            $equipos,
            $this->eventoIntercarreras()
        );

        // El partido 1 vs 2 (misma carrera, ambos grupales) no debe existir
        foreach ($partidos as $partido) {
            $local = $partido['local_id'];
            $visitante = $partido['visitante_id'];
            $esPar1vs2 = ($local === 1 && $visitante === 2)
                || ($local === 2 && $visitante === 1);
            $this->assertFalse(
                $esPar1vs2,
                'No debe generarse partido entre equipos de la misma carrera en modo intercarreras'
            );
        }
    }

    // ─── sorteoAleatorio ──────────────────────────────────────────────────────

    public function test_sorteo_libre_devuelve_todos_los_participantes(): void
    {
        $participantes = $this->makeParticipants(8);

        $resultado = $this->service->sorteoAleatorio($participantes, $this->eventoLibre());

        $this->assertCount(8, $resultado);
        $idsEsperados = array_column($participantes, 'id');
        $idsResultado = array_column($resultado, 'id');
        sort($idsEsperados);
        sort($idsResultado);
        $this->assertSame($idsEsperados, $idsResultado);
    }

    public function test_sorteo_intercarreras_devuelve_todos_los_participantes(): void
    {
        $participantes = [
            ['id' => 1, 'carrera_id' => 10, 'tipo_inscripcion' => 'grupal'],
            ['id' => 2, 'carrera_id' => 10, 'tipo_inscripcion' => 'grupal'],
            ['id' => 3, 'carrera_id' => 20, 'tipo_inscripcion' => 'grupal'],
            ['id' => 4, 'carrera_id' => null, 'tipo_inscripcion' => 'individual'],
        ];

        $resultado = $this->service->sorteoAleatorio($participantes, $this->eventoIntercarreras());

        $this->assertCount(4, $resultado);
        $ids = array_column($resultado, 'id');
        sort($ids);
        $this->assertSame([1, 2, 3, 4], $ids);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function makeParticipants(int $n): array
    {
        return array_map(
            fn ($i) => ['id' => $i, 'carrera_id' => ($i % 3) + 1, 'tipo_inscripcion' => 'grupal'],
            range(1, $n)
        );
    }

    private function eventoLibre(): EventoConfiguracion
    {
        return new EventoConfiguracion(['tipo_evento' => 'libre']);
    }

    private function eventoIntercarreras(): EventoConfiguracion
    {
        return new EventoConfiguracion(['tipo_evento' => 'intercarreras']);
    }
}
