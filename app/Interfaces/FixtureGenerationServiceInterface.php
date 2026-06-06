<?php

namespace App\Interfaces;

use App\Models\EventoConfiguracion;

interface FixtureGenerationServiceInterface
{
    /**
     * Shuffles participants ensuring intercarreras constraints
     * (teams from the same career are not placed in the same group).
     */
    public function sorteoAleatorio(array $participantes, EventoConfiguracion $evento): array;

    /**
     * Returns a [0..cantidadSeries-1] indexed array with the team count
     * each series should receive.
     *
     * @param  array  $equiposOverride  Indexed [0 => n, 1 => n, ...]. 0 = auto-distribute.
     */
    public function distribuirEquiposEnSeries(array $participantes, int $cantidadSeries, array $equiposOverride = []): array;

    /**
     * Standard Round Robin algorithm. Returns an array of
     * ['local_id', 'visitante_id', 'jornada'] entries.
     */
    public function generarPartidosRoundRobin(array $equipos, EventoConfiguracion $evento): array;

    /**
     * Human-readable phase label based on participant count.
     * (Final, Semifinal, Cuartos de Final, etc.)
     */
    public function getNombreFase(int $count): string;

    /**
     * Creates all Series and Partidos for the fixture inside a DB transaction.
     *
     * $configuracion expected keys:
     *   equipos_override[i]   => int         (0 = auto-distribute)
     *   cuantos_clasifican[i] => int
     *   lugar_por_serie[i]    => int|null
     *   fecha_por_serie[i]    => string|null
     *   hora_por_serie[i]     => string|null
     *
     * @throws \RuntimeException on DB failure
     */
    public function crearSeriesYPartidos(
        int $eventoId,
        int $disciplinaId,
        array $participantesIds,
        int $cantidadSeries,
        array $configuracion
    ): void;

    /**
     * Advances to the next elimination round from all finished group-stage
     * series of the given event and discipline.
     *
     * Returns the generated phase name (e.g. "Semifinal") for use in
     * success messages.
     *
     * @throws \InvalidArgumentException when series are not ready
     * @throws \RuntimeException on DB failure
     */
    public function avanzarFaseEliminatoria(int $eventoId, int $disciplinaId, ?string $fecha): string;
}
