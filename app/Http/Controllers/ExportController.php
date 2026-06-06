<?php

namespace App\Http\Controllers;

use App\Exports\PreinscripcionesExport;
use App\Exports\TablaPosicionesExport;
use App\Models\EventoConfiguracion;
use App\Models\Serie;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permiso:preinscripciones,ver']);
    }

    // ── Excel: lista de pre-inscripciones ────────────────────────────────
    public function preinscripcionesExcel(Request $request)
    {
        $tipo = $request->tipo_evento;
        $disciplina = $request->disciplina_id ? (int) $request->disciplina_id : null;
        $estado = $request->estado;

        $nombreArchivo = 'preinscripciones'
            .($tipo ? "_{$tipo}" : '')
            .($disciplina ? "_disc{$disciplina}" : '')
            .($estado ? "_{$estado}" : '')
            .'_'.now()->format('Ymd_His')
            .'.xlsx';

        return Excel::download(
            new PreinscripcionesExport($tipo, $disciplina, $estado),
            $nombreArchivo
        );
    }

    // ── Excel: tabla de posiciones de una serie ───────────────────────────
    public function tablaPosicionesExcel(int $serieId)
    {
        $serie = Serie::with(['disciplina', 'eventoConfiguracion'])->findOrFail($serieId);

        $nombreArchivo = 'posiciones_'
            .str_replace([' ', '/'], '_', $serie->nombre_serie)
            .'_'.now()->format('Ymd_His')
            .'.xlsx';

        return Excel::download(
            new TablaPosicionesExport($serie->id, $serie->nombre_serie),
            $nombreArchivo
        );
    }

    // ── PDF: fixture completo de un evento ───────────────────────────────
    public function fixturePdf(int $eventoId)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);

        $series = Serie::where('evento_configuracion_id', $eventoId)
            ->with([
                'disciplina',
                'partidos.equipoLocal',
                'partidos.equipoVisitante',
                'partidos.lugar',
            ])
            ->orderBy('numero_serie')
            ->get();

        $pdf = Pdf::loadView('exports.fixture_pdf', compact('evento', 'series'))
            ->setPaper('letter', 'landscape')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $nombre = 'fixture_'.str_replace(' ', '_', $evento->nombre).'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($nombre);
    }

    // ── PDF: tabla de posiciones de una serie ────────────────────────────
    public function tablaPosicionesPdf(int $serieId)
    {
        $serie = Serie::with([
            'disciplina',
            'eventoConfiguracion',
            'estadisticas.equipo.carrera',
            'estadisticas.equipo.facultad',
        ])->findOrFail($serieId);

        $tablaPosiciones = $serie->tablaPosiciones->load('equipo.carrera', 'equipo.facultad');

        $pdf = Pdf::loadView('exports.posiciones_pdf', compact('serie', 'tablaPosiciones'))
            ->setPaper('letter', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $nombre = 'posiciones_'.str_replace([' ', '/'], '_', $serie->nombre_serie).'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($nombre);
    }
}
