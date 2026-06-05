<?php

namespace App\Http\Controllers;

use App\Models\EventoConfiguracion;
use App\Models\Partido;
use App\Models\Preinscripcion;
use App\Models\Serie;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ── KPIs de Pre-inscripciones ────────────────────────────────────────
        $preinscPendientes = Preinscripcion::pendientes()->count();
        $preinscHabilitadas = Preinscripcion::habilitados()->count();
        $preinscObservadas = Preinscripcion::observados()->count();
        $totalPreinsc = Preinscripcion::count();

        // ── KPIs de Fixture ──────────────────────────────────────────────────
        $seriesEnCurso = Serie::where('estado', 'en_curso')->count();
        $seriesFinalizadas = Serie::where('estado', 'finalizado')->count();
        $totalPartidos = Partido::count();
        $partidosPendientes = Partido::where('estado', 'programado')->count();
        $partidosFinalizados = Partido::where('estado', 'finalizado')->count();

        // ── Eventos activos ─────────────────────────────────────────────────
        $eventosActivos = EventoConfiguracion::where('activo', true)
            ->orderBy('fecha_inicio')
            ->get();

        // ── Alertas: series grupales con partidos sin resultado ──────────────
        $seriesConPendientes = Serie::where('estado', 'en_curso')
            ->withCount(['partidos as pendientes_count' => fn ($q) => $q->where('estado', 'programado'),
            ])
            ->having('pendientes_count', '>', 0)
            ->with('disciplina', 'eventoConfiguracion')
            ->orderByDesc('pendientes_count')
            ->limit(6)
            ->get();

        // ── Pre-inscripciones recientes pendientes de revisión ───────────────
        $recientesPendientes = Preinscripcion::pendientes()
            ->with(['disciplina', 'carrera', 'facultad'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // ── Distribución por estado (para gráfico doughnut) ─────────────────
        $distribucionEstados = [
            'labels' => ['Pendientes', 'Habilitados', 'Observados'],
            'data' => [$preinscPendientes, $preinscHabilitadas, $preinscObservadas],
            'colors' => ['#fb6340', '#2dce89', '#f5365c'],
        ];

        // ── Distribución por disciplina (para gráfico barras) ───────────────
        $porDisciplina = Preinscripcion::select('disciplina_id', DB::raw('count(*) as total'))
            ->groupBy('disciplina_id')
            ->with('disciplina')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $graficoDisciplina = [
            'labels' => $porDisciplina->map(fn ($p) => $p->disciplina->nombre ?? 'Sin disciplina')->toArray(),
            'data' => $porDisciplina->pluck('total')->toArray(),
        ];

        // ── Próximos partidos (con fecha asignada, no finalizados) ───────────
        $proximosPartidos = Partido::where('estado', 'programado')
            ->whereNotNull('fecha')
            ->with(['equipoLocal', 'equipoVisitante', 'lugar', 'serie.disciplina'])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit(5)
            ->get();

        // ── Total usuarios (solo visible para admins) ────────────────────────
        $totalUsuarios = User::count();

        return view('home', compact(
            'preinscPendientes', 'preinscHabilitadas', 'preinscObservadas', 'totalPreinsc',
            'seriesEnCurso', 'seriesFinalizadas',
            'totalPartidos', 'partidosPendientes', 'partidosFinalizados',
            'eventosActivos',
            'seriesConPendientes',
            'recientesPendientes',
            'distribucionEstados',
            'graficoDisciplina',
            'proximosPartidos',
            'totalUsuarios'
        ));
    }
}
