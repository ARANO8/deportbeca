<?php

namespace App\Services;

use App\Interfaces\FixtureGenerationServiceInterface;
use App\Models\EventoConfiguracion;
use App\Models\Partido;
use App\Models\Preinscripcion;
use App\Models\Serie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixtureGenerationService implements FixtureGenerationServiceInterface
{
    // ─────────────────────────────────────────────────────────────────────────
    // Pure helpers (no I/O)
    // ─────────────────────────────────────────────────────────────────────────

    public function sorteoAleatorio(array $participantes, EventoConfiguracion $evento): array
    {
        $esIntercarreras = ($evento->tipo_evento === 'intercarreras');

        if (! $esIntercarreras) {
            shuffle($participantes);

            return $participantes;
        }

        $individuales = [];
        $grupales = [];

        foreach ($participantes as $p) {
            if ($p['tipo_inscripcion'] == 'individual') {
                $individuales[] = $p;
            } else {
                $grupales[] = $p;
            }
        }

        $grupalesPorCarrera = [];
        foreach ($grupales as $g) {
            $carreraId = $g['carrera_id'] ?? 0;
            if (! isset($grupalesPorCarrera[$carreraId])) {
                $grupalesPorCarrera[$carreraId] = [];
            }
            $grupalesPorCarrera[$carreraId][] = $g;
        }

        $carreras = array_keys($grupalesPorCarrera);
        shuffle($carreras);

        $mezclados = [];
        foreach ($carreras as $carreraId) {
            foreach ($grupalesPorCarrera[$carreraId] as $equipo) {
                $mezclados[] = $equipo;
            }
        }

        shuffle($mezclados);

        $todos = array_merge($mezclados, $individuales);
        shuffle($todos);

        return $todos;
    }

    public function distribuirEquiposEnSeries(array $participantes, int $cantidadSeries, array $equiposOverride = []): array
    {
        $totalParticipantes = count($participantes);
        $equiposPorSerie = [];

        for ($i = 0; $i < $cantidadSeries; $i++) {
            $especificado = (int) ($equiposOverride[$i] ?? 0);
            if ($especificado > 0) {
                $equiposPorSerie[$i] = $especificado;
            }
        }

        if (empty($equiposPorSerie)) {
            $base = (int) floor($totalParticipantes / $cantidadSeries);
            $resto = $totalParticipantes % $cantidadSeries;

            for ($i = 0; $i < $cantidadSeries; $i++) {
                $equiposPorSerie[$i] = $base + ($i < $resto ? 1 : 0);
            }

            shuffle($equiposPorSerie);
        }

        return $equiposPorSerie;
    }

    /**
     * Standard Round Robin con agrupacion correcta de jornadas.
     */
    public function generarPartidosRoundRobin(array $equipos, EventoConfiguracion $evento): array
    {
        $n = count($equipos);
        if ($n < 2) {
            return [];
        }

        $esIntercarreras = ($evento->tipo_evento === 'intercarreras');

        if ($n % 2 !== 0) {
            $equipos[] = null;
            $n++;
        }

        $order = range(0, $n - 1);
        $partidos = [];

        for ($ronda = 0; $ronda < $n - 1; $ronda++) {
            $jornada = $ronda + 1;

            for ($i = 0; $i < $n / 2; $i++) {
                $eq1 = $equipos[$order[$i]];
                $eq2 = $equipos[$order[$n - 1 - $i]];

                if ($eq1 === null || $eq2 === null) {
                    continue;
                }

                if ($esIntercarreras && $this->mismaCarreraGrupal($eq1, $eq2)) {
                    continue;
                }

                if (rand(0, 1)) {
                    [$eq1, $eq2] = [$eq2, $eq1];
                }

                $partidos[] = [
                    'local_id' => $eq1['id'],
                    'visitante_id' => $eq2['id'],
                    'jornada' => $jornada,
                ];
            }

            $ultimo = array_pop($order);
            array_splice($order, 1, 0, [$ultimo]);
        }

        return $partidos;
    }

    public function getNombreFase(int $count): string
    {
        return match (true) {
            $count <= 2 => 'Final',
            $count <= 4 => 'Semifinal',
            $count <= 8 => 'Cuartos de Final',
            $count <= 16 => 'Octavos de Final',
            default => 'Fase Eliminatoria',
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Transactional operations
    // ─────────────────────────────────────────────────────────────────────────

    public function crearSeriesYPartidos(
        int $eventoId,
        int $disciplinaId,
        array $participantesIds,
        int $cantidadSeries,
        array $configuracion
    ): void {
        $evento = EventoConfiguracion::findOrFail($eventoId);
        $participantes = Preinscripcion::whereIn('id', $participantesIds)->get()->toArray();

        $esIndividual = ! empty($participantes)
            && ($participantes[0]['tipo_inscripcion'] ?? '') === 'individual';

        $participantes = $this->sorteoAleatorio($participantes, $evento);

        $equiposOverride = $configuracion['equipos_override'] ?? [];
        $equiposPorSerie = $this->distribuirEquiposEnSeries($participantes, $cantidadSeries, $equiposOverride);

        $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        DB::beginTransaction();

        try {
            $indiceParticipante = 0;

            for ($i = 0; $i < $cantidadSeries; $i++) {
                $cantidadEquiposSerie = $equiposPorSerie[$i] ?? 0;

                if ($cantidadEquiposSerie < 1) {
                    continue;
                }

                $equiposSerie = array_slice($participantes, $indiceParticipante, $cantidadEquiposSerie);
                $indiceParticipante += $cantidadEquiposSerie;

                if (empty($equiposSerie)) {
                    continue;
                }

                $cuantosClasifican = (int) ($configuracion['cuantos_clasifican'][$i] ?? min(2, count($equiposSerie)));
                $timestamp = now()->format('Ymd_His');

                // Crear la serie SIN equipos_ids (ya no existe la columna)
                $serie = Serie::create([
                    'evento_configuracion_id' => $eventoId,
                    'disciplina_id' => $disciplinaId,
                    'nombre_serie' => 'Serie '.($letras[$i] ?? $i + 1).' ('.$timestamp.')',
                    'numero_serie' => $i + 1,
                    'cantidad_equipos' => count($equiposSerie),
                    'estado' => 'en_curso',
                    'tipo_competencia' => 'todos_contra_todos',
                    'cuantos_clasifican' => $cuantosClasifican,
                ]);

                // Asociar equipos via tabla junction con su orden
                $pivotData = [];
                foreach ($equiposSerie as $orden => $equipo) {
                    $pivotData[$equipo['id']] = [
                        'orden' => $orden,
                        'es_clasificado' => false,
                    ];
                }
                $serie->preinscripciones()->attach($pivotData);

                // Generar partidos Round Robin si es modalidad grupal
                if (! $esIndividual && count($equiposSerie) >= 2) {
                    $partidos = $this->generarPartidosRoundRobin($equiposSerie, $evento);

                    foreach ($partidos as $partido) {
                        Partido::create([
                            'serie_id' => $serie->id,
                            'evento_configuracion_id' => $eventoId,
                            'disciplina_id' => $disciplinaId,
                            'equipo_local_id' => $partido['local_id'],
                            'equipo_visitante_id' => $partido['visitante_id'],
                            'jornada' => $partido['jornada'],
                            'lugar_id' => $configuracion['lugar_por_serie'][$i] ?? null,
                            'fecha' => $configuracion['fecha_por_serie'][$i] ?? null,
                            'hora_inicio' => $configuracion['hora_por_serie'][$i] ?? null,
                            'estado' => 'programado',
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FixtureGenerationService::crearSeriesYPartidos - '.$e->getMessage());
            throw new \RuntimeException('Error al crear series y partidos: '.$e->getMessage(), 0, $e);
        }
    }

    public function avanzarFaseEliminatoria(int $eventoId, int $disciplinaId, ?string $fecha): string
    {
        $seriesGrupo = Serie::where('evento_configuracion_id', $eventoId)
            ->where('disciplina_id', $disciplinaId)
            ->where('tipo_competencia', 'todos_contra_todos')
            ->where('estado', 'en_curso')
            ->get();

        if ($seriesGrupo->isEmpty()) {
            throw new \InvalidArgumentException('No hay series de grupos activas para esta disciplina.');
        }

        foreach ($seriesGrupo as $serie) {
            $pendientes = $serie->partidos()->where('estado', '!=', 'finalizado')->count();
            if ($pendientes > 0) {
                throw new \InvalidArgumentException(
                    "La serie \"{$serie->nombre_serie}\" aun tiene {$pendientes} partido(s) sin finalizar."
                );
            }
        }

        DB::beginTransaction();

        try {
            $clasificadosIds = collect();

            foreach ($seriesGrupo as $serie) {
                $tabla = $serie->tablaPosiciones;
                $topN = $tabla->take($serie->cuantos_clasifican)->pluck('preinscripcion_id');
                $clasificadosIds = $clasificadosIds->merge($topN);

                // Marcar clasificados en la tabla junction (reemplaza clasificados_ids JSON)
                foreach ($topN as $id) {
                    $serie->preinscripciones()->updateExistingPivot($id, ['es_clasificado' => true]);
                }

                $serie->estado = 'finalizado';
                $serie->save();
            }

            $ids = $clasificadosIds->unique()->shuffle()->values()->toArray();
            $count = count($ids);

            if ($count < 2) {
                DB::rollBack();
                throw new \InvalidArgumentException('Se necesitan al menos 2 clasificados para generar la siguiente fase.');
            }

            $faseNombre = $this->getNombreFase($count);
            $timestamp = now()->format('Ymd_His');

            // Crear la nueva serie de fase eliminatoria SIN equipos_ids
            $nuevaSerie = Serie::create([
                'evento_configuracion_id' => $eventoId,
                'disciplina_id' => $disciplinaId,
                'nombre_serie' => $faseNombre.' ('.$timestamp.')',
                'numero_serie' => $seriesGrupo->count() + 1,
                'cantidad_equipos' => $count,
                'estado' => 'en_curso',
                'tipo_competencia' => 'eliminatoria',
                'cuantos_clasifican' => intdiv($count, 2),
            ]);

            // Asociar equipos clasificados via tabla junction
            $pivotData = [];
            foreach ($ids as $orden => $id) {
                $pivotData[$id] = [
                    'orden' => $orden,
                    'es_clasificado' => false,
                ];
            }
            $nuevaSerie->preinscripciones()->attach($pivotData);

            // Generar partidos eliminatorios
            for ($i = 0; $i < intdiv($count, 2); $i++) {
                Partido::create([
                    'serie_id' => $nuevaSerie->id,
                    'evento_configuracion_id' => $eventoId,
                    'disciplina_id' => $disciplinaId,
                    'equipo_local_id' => $ids[$i],
                    'equipo_visitante_id' => $ids[$count - 1 - $i],
                    'jornada' => $i + 1,
                    'fecha' => $fecha,
                    'estado' => 'programado',
                ]);
            }

            DB::commit();

            return $faseNombre;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FixtureGenerationService::avanzarFaseEliminatoria - '.$e->getMessage());
            throw new \RuntimeException('Error al generar la siguiente fase: '.$e->getMessage(), 0, $e);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private utilities
    // ─────────────────────────────────────────────────────────────────────────

    private function mismaCarreraGrupal(array $eq1, array $eq2): bool
    {
        $c1 = $eq1['carrera_id'] ?? null;
        $c2 = $eq2['carrera_id'] ?? null;

        return $c1 && $c2 && $c1 == $c2
            && ($eq1['tipo_inscripcion'] ?? '') === 'grupal'
            && ($eq2['tipo_inscripcion'] ?? '') === 'grupal';
    }
}
