<?php

namespace App\Http\Controllers;

use App\Models\Estadistica;
use App\Models\FixtureConfiguracion;
use App\Models\Partido;
use App\Models\Preinscripcion;
use App\Models\Serie;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    /**
     * BUG-01 FIX: la vista 'calificaciones.index' no existía.
     * Ahora detecta si la disciplina es individual o grupal y retorna la vista correcta.
     */
    public function index($serieId)
    {
        $serie = Serie::with([
            'disciplina',
            'eventoConfiguracion',
            'partidos.equipoLocal',
            'partidos.equipoVisitante',
        ])->findOrFail($serieId);

        // Usar la relacion many-to-many normalizada (reemplaza whereIn sobre JSON)
        $participantes = $serie->preinscripciones()
            ->with(['carrera', 'facultad'])
            ->get();

        $estadisticas = Estadistica::where('serie_id', $serieId)
            ->get()
            ->keyBy('preinscripcion_id');

        $esIndividual = $participantes->isNotEmpty()
            && $participantes->first()->tipo_inscripcion === 'individual';

        if ($esIndividual) {
            return view('calificaciones.individual', compact('serie', 'participantes', 'estadisticas'));
        }

        $tablaPosiciones = $serie->tablaPosiciones;
        $todosFinalizados = $serie->partidos->isNotEmpty()
            && $serie->partidos->every(fn ($p) => $p->estado === 'finalizado');

        return view('calificaciones.index', compact(
            'serie', 'participantes', 'estadisticas', 'tablaPosiciones', 'todosFinalizados'
        ));
    }

    /**
     * BUG-04 FIX: ahora guarda también el campo 'marca'.
     * BUG-03 FIX: posicion_final y marca ya están en $fillable del modelo Estadistica.
     */
    public function guardarPosiciones(Request $request, $serieId)
    {
        $request->validate([
            'posiciones' => 'required|array',
            'posiciones.*' => 'nullable|integer|min:1',
            'marca' => 'nullable|array',
            'marca.*' => 'nullable|string|max:50',
        ]);

        Serie::findOrFail($serieId);

        // Pre-cargar nombres para evitar el error NOT NULL en nombre_equipo
        $equiposIds = array_keys($request->posiciones);
        $participantes = Preinscripcion::whereIn('id', $equiposIds)->get()->keyBy('id');

        foreach ($request->posiciones as $equipoId => $posicion) {
            if (! $posicion) {
                continue;
            }

            $nombreEquipo = $participantes[$equipoId]->nombre_participante ?? "Participante {$equipoId}";

            Estadistica::updateOrCreate(
                ['serie_id' => $serieId, 'preinscripcion_id' => $equipoId],
                [
                    'nombre_equipo' => $nombreEquipo,
                    'posicion_final' => $posicion,
                    'marca' => $request->input("marca.{$equipoId}"),
                ]
            );
        }

        return redirect()->back()->with('success', 'Posiciones guardadas correctamente');
    }

    /**
     * BUG-02 FIX: reemplaza la lógica de acumulación por un recálculo completo
     * desde los partidos finalizados, evitando doble conteo al corregir resultados.
     */
    public function guardarResultadoGrupal(Request $request, $partidoId)
    {
        $request->validate([
            'goles_local' => 'required|integer|min:0',
            'goles_visitante' => 'required|integer|min:0',
            'tarjetas_amarillas_local' => 'nullable|integer|min:0',
            'tarjetas_rojas_local' => 'nullable|integer|min:0',
            'tarjetas_amarillas_visitante' => 'nullable|integer|min:0',
            'tarjetas_rojas_visitante' => 'nullable|integer|min:0',
        ]);

        $partido = Partido::findOrFail($partidoId);

        $partido->goles_local = $request->goles_local;
        $partido->goles_visitante = $request->goles_visitante;
        $partido->tarjetas_amarillas_local = $request->input('tarjetas_amarillas_local', 0);
        $partido->tarjetas_rojas_local = $request->input('tarjetas_rojas_local', 0);
        $partido->tarjetas_amarillas_visitante = $request->input('tarjetas_amarillas_visitante', 0);
        $partido->tarjetas_rojas_visitante = $request->input('tarjetas_rojas_visitante', 0);
        $partido->estado = 'finalizado';
        $partido->save();

        $this->recalcularEstadisticasGrupal($partido->serie_id);

        return response()->json(['success' => true]);
    }

    /**
     * Recalcula todas las estadísticas de la serie desde cero
     * basándose únicamente en los partidos con estado 'finalizado'.
     */
    private function recalcularEstadisticasGrupal(int $serieId): void
    {
        $serie = Serie::findOrFail($serieId);

        // Puntos configurables: buscar en fixture_configuraciones, o usar defaults (3/1/0)
        $config = FixtureConfiguracion::where('evento_configuracion_id', $serie->evento_configuracion_id)
            ->where('disciplina_id', $serie->disciplina_id)
            ->first();
        $ptsGanador = $config?->puntos_ganador ?? 3;
        $ptsEmpate = $config?->puntos_empate ?? 1;
        $ptsPerdedor = $config?->puntos_perdedor ?? 0;

        // Obtener equipos via relacion many-to-many normalizada
        $equipos = $serie->preinscripciones()->get()->keyBy('id');
        $equiposIds = $equipos->keys()->toArray();

        // Inicializar acumuladores en cero para todos los equipos
        $stats = [];
        foreach ($equiposIds as $id) {
            $stats[$id] = ['pj' => 0, 'pg' => 0, 'pe' => 0, 'pp' => 0, 'gf' => 0, 'gc' => 0, 'pts' => 0, 'ta' => 0, 'tr' => 0];
        }

        // Sumar solo los partidos ya finalizados
        $partidos = Partido::where('serie_id', $serieId)
            ->where('estado', 'finalizado')
            ->get();

        foreach ($partidos as $p) {
            foreach ([$p->equipo_local_id, $p->equipo_visitante_id] as $id) {
                if (! isset($stats[$id])) {
                    $stats[$id] = ['pj' => 0, 'pg' => 0, 'pe' => 0, 'pp' => 0, 'gf' => 0, 'gc' => 0, 'pts' => 0, 'ta' => 0, 'tr' => 0];
                }
            }

            $stats[$p->equipo_local_id]['pj']++;
            $stats[$p->equipo_visitante_id]['pj']++;
            $stats[$p->equipo_local_id]['gf'] += $p->goles_local;
            $stats[$p->equipo_local_id]['gc'] += $p->goles_visitante;
            $stats[$p->equipo_visitante_id]['gf'] += $p->goles_visitante;
            $stats[$p->equipo_visitante_id]['gc'] += $p->goles_local;
            $stats[$p->equipo_local_id]['ta'] += $p->tarjetas_amarillas_local;
            $stats[$p->equipo_local_id]['tr'] += $p->tarjetas_rojas_local;
            $stats[$p->equipo_visitante_id]['ta'] += $p->tarjetas_amarillas_visitante;
            $stats[$p->equipo_visitante_id]['tr'] += $p->tarjetas_rojas_visitante;

            if ($p->goles_local > $p->goles_visitante) {
                $stats[$p->equipo_local_id]['pg']++;
                $stats[$p->equipo_local_id]['pts'] += $ptsGanador;
                $stats[$p->equipo_visitante_id]['pp']++;
                $stats[$p->equipo_visitante_id]['pts'] += $ptsPerdedor;
            } elseif ($p->goles_visitante > $p->goles_local) {
                $stats[$p->equipo_visitante_id]['pg']++;
                $stats[$p->equipo_visitante_id]['pts'] += $ptsGanador;
                $stats[$p->equipo_local_id]['pp']++;
                $stats[$p->equipo_local_id]['pts'] += $ptsPerdedor;
            } else {
                $stats[$p->equipo_local_id]['pe']++;
                $stats[$p->equipo_visitante_id]['pe']++;
                $stats[$p->equipo_local_id]['pts'] += $ptsEmpate;
                $stats[$p->equipo_visitante_id]['pts'] += $ptsEmpate;
            }
        }

        foreach ($stats as $equipoId => $s) {
            $nombreEquipo = $equipos[$equipoId]->nombre_participante ?? "Equipo {$equipoId}";

            Estadistica::updateOrCreate(
                ['serie_id' => $serieId, 'preinscripcion_id' => $equipoId],
                [
                    'nombre_equipo' => $nombreEquipo,
                    'pj' => $s['pj'],
                    'pg' => $s['pg'],
                    'pe' => $s['pe'],
                    'pp' => $s['pp'],
                    'gf' => $s['gf'],
                    'gc' => $s['gc'],
                    'dg' => $s['gf'] - $s['gc'],
                    'pts' => $s['pts'],
                    'tarjetas_amarillas' => $s['ta'],
                    'tarjetas_rojas' => $s['tr'],
                ]
            );
        }
    }
}
