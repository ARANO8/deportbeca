<?php

namespace App\Http\Controllers;

use App\Models\EventoConfiguracion;
use App\Models\Serie;

class PortalController extends Controller
{
    /**
     * Lista de eventos activos con conteo de series disponibles.
     */
    public function index()
    {
        $eventos = EventoConfiguracion::where('activo', true)
            ->withCount('series')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('portal.index', compact('eventos'));
    }

    /**
     * Detalle de un evento: series agrupadas por disciplina.
     */
    public function evento(int $eventoId)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);

        $series = Serie::with([
            'disciplina',
            'estadisticas',
            'partidos',
        ])
            ->where('evento_configuracion_id', $eventoId)
            ->orderBy('nombre_serie')
            ->get();

        $seriesPorDisciplina = $series->groupBy('disciplina_id');

        return view('portal.evento', compact('evento', 'seriesPorDisciplina'));
    }

    /**
     * Tabla de posiciones publica de una serie.
     */
    public function serie(int $serieId)
    {
        $serie = Serie::with(['disciplina', 'eventoConfiguracion'])->findOrFail($serieId);

        $hayPosiciones = $serie->estadisticas()->whereNotNull('posicion_final')->exists();

        if ($hayPosiciones) {
            $tablaPosiciones = $serie->estadisticas()
                ->with('equipo')
                ->orderByRaw('CASE WHEN posicion_final IS NULL THEN 9999 ELSE posicion_final END ASC')
                ->get();
            $esIndividual = true;
        } else {
            $tablaPosiciones = $serie->estadisticas()
                ->with('equipo')
                ->orderBy('pts', 'desc')
                ->orderBy('dg', 'desc')
                ->orderBy('gf', 'desc')
                ->get();
            $esIndividual = false;
        }

        return view('portal.serie', compact('serie', 'tablaPosiciones', 'esIndividual'));
    }

    /**
     * Fixture y resultados de un evento (vista publica).
     */
    public function fixture(int $eventoId)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);

        $series = Serie::with([
            'disciplina',
            'partidos' => function ($q) {
                $q->with(['equipoLocal', 'equipoVisitante', 'lugar'])
                    ->orderBy('jornada')
                    ->orderBy('fecha');
            },
        ])
            ->where('evento_configuracion_id', $eventoId)
            ->orderBy('nombre_serie')
            ->get();

        return view('portal.fixture', compact('evento', 'series'));
    }
}
