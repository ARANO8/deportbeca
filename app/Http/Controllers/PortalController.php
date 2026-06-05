<?php

namespace App\Http\Controllers;

use App\Models\EventoConfiguracion;
use App\Models\Serie;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Lista de eventos activos con conteo de series disponibles.
     */
    public function index(Request $request)
    {
        $busqueda = $request->get('q');

        $eventos = EventoConfiguracion::where('activo', true)
            ->withCount('series')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('portal.index', compact('eventos', 'busqueda'));
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
    public function serie(int $serieId, Request $request)
    {
        $serie = Serie::with(['disciplina', 'eventoConfiguracion'])->findOrFail($serieId);
        $busqueda = $request->get('q');

        $hayPosiciones = $serie->estadisticas()->whereNotNull('posicion_final')->exists();

        $query = $serie->estadisticas()->with('equipo');

        if ($busqueda) {
            $query->whereHas('equipo', function ($q) use ($busqueda) {
                $q->where('nombre_equipo', 'like', '%'.$busqueda.'%')
                    ->orWhere('representante_nombre', 'like', '%'.$busqueda.'%');
            });
        }

        if ($hayPosiciones) {
            $tablaPosiciones = $query
                ->orderByRaw('CASE WHEN posicion_final IS NULL THEN 9999 ELSE posicion_final END ASC')
                ->get();
            $esIndividual = true;
        } else {
            $tablaPosiciones = $query
                ->orderBy('pts', 'desc')
                ->orderBy('dg', 'desc')
                ->orderBy('gf', 'desc')
                ->get();
            $esIndividual = false;
        }

        return view('portal.serie', compact('serie', 'tablaPosiciones', 'esIndividual', 'busqueda'));
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
