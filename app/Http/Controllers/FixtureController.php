<?php

namespace App\Http\Controllers;

use App\Interfaces\FixtureGenerationServiceInterface;
use App\Models\Discipline;
use App\Models\EventoConfiguracion;
use App\Models\Lugar;
use App\Models\Partido;
use App\Models\Preinscripcion;
use App\Models\Serie;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    public function __construct(
        private FixtureGenerationServiceInterface $fixtureGenerator
    ) {}

    public function index()
    {
        $eventos = EventoConfiguracion::where('activo', true)->get();

        return view('fixture.index', compact('eventos'));
    }

    public function getDisciplinas($eventoId)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);
        $disciplinasIds = is_array($evento->disciplinas_ids)
            ? $evento->disciplinas_ids
            : json_decode($evento->disciplinas_ids ?? '[]', true);

        if (empty($disciplinasIds)) {
            return response()->json([]);
        }

        $disciplinas = Discipline::whereIn('id', $disciplinasIds)
            ->where('status', 'active')
            ->get();

        $result = [];
        foreach ($disciplinas as $disciplina) {
            $subdisciplinas = Discipline::where('parent_id', $disciplina->id)
                ->where('status', 'active')
                ->get();

            if ($subdisciplinas->count() > 0) {
                foreach ($subdisciplinas as $sub) {
                    $result[] = [
                        'id' => $sub->id,
                        'nombre' => $sub->nombre,
                        'parent' => $disciplina->nombre,
                    ];
                }
            } else {
                $result[] = [
                    'id' => $disciplina->id,
                    'nombre' => $disciplina->nombre,
                    'parent' => null,
                ];
            }
        }

        return response()->json($result);
    }

    public function participantes($eventoId, $disciplinaId)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);
        $disciplina = Discipline::findOrFail($disciplinaId);

        $participantes = Preinscripcion::where('disciplina_id', $disciplinaId)
            ->where('estado', 'habilitado')
            ->where('tipo_evento', $evento->tipo_evento)
            ->with(['carrera', 'facultad'])
            ->get();

        return view('fixture.participantes', compact('evento', 'disciplina', 'participantes'));
    }

    public function guardarParticipantes(Request $request, $eventoId, $disciplinaId)
    {
        session(['participantes_fixture' => $request->participantes_ids]);
        session(['disciplina_fixture' => $disciplinaId]);
        session(['evento_fixture' => $eventoId]);

        return redirect()->route('fixture.configurar.series', [$eventoId, $disciplinaId]);
    }

    public function configurarSeries($eventoId, $disciplinaId)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);
        $disciplina = Discipline::findOrFail($disciplinaId);
        $participantesIds = session('participantes_fixture', []);
        $total = count($participantesIds);
        $lugares = Lugar::where('status', 'active')->get();

        return view('fixture.configurar_series', compact('evento', 'disciplina', 'total', 'participantesIds', 'lugares'));
    }

    public function generarFixture(Request $request, $eventoId)
    {
        $request->validate([
            'cantidad_series' => 'required|integer|min:1|max:12',
        ]);

        $cantidadSeries = (int) $request->cantidad_series;
        $participantesIds = session('participantes_fixture', []);
        $disciplinaId = session('disciplina_fixture');

        if (empty($participantesIds)) {
            return redirect()->route('fixture.index')->with('error', 'No hay participantes seleccionados');
        }

        $configuracion = [];
        for ($i = 0; $i < $cantidadSeries; $i++) {
            $configuracion['equipos_override'][$i] = (int) $request->input("equipos_serie_{$i}", 0);
            $configuracion['cuantos_clasifican'][$i] = (int) $request->input("cuantos_clasifican.{$i}", 2);
            $configuracion['lugar_por_serie'][$i] = $request->input("lugar_serie_{$i}");
            $configuracion['fecha_por_serie'][$i] = $request->input("fecha_serie_{$i}");
            $configuracion['hora_por_serie'][$i] = $request->input("hora_serie_{$i}");
        }

        try {
            $this->fixtureGenerator->crearSeriesYPartidos(
                (int) $eventoId,
                (int) $disciplinaId,
                $participantesIds,
                $cantidadSeries,
                $configuracion
            );

            session()->forget(['participantes_fixture', 'disciplina_fixture', 'evento_fixture']);

            return redirect()->route('fixture.mis.fixtures')
                ->with('success', 'Fixture generado con sorteo aleatorio');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function generarSiguienteFase(Request $request, $eventoId, $disciplinaId)
    {
        try {
            $faseNombre = $this->fixtureGenerator->avanzarFaseEliminatoria(
                (int) $eventoId,
                (int) $disciplinaId,
                $request->input('fecha_siguiente_fase')
            );

            return redirect()->route('fixture.mis.fixtures')
                ->with('success', "{$faseNombre} generada exitosamente.");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function misFixtures()
    {
        $eventosIds = Serie::distinct()->pluck('evento_configuracion_id');
        $eventos = EventoConfiguracion::whereIn('id', $eventosIds)->get();

        return view('fixture.mis_fixtures', compact('eventos'));
    }

    public function verFixtureSerie($serieId)
    {
        $serie = Serie::with([
            'partidos.equipoLocal',
            'partidos.equipoVisitante',
            'partidos.lugar',
            'disciplina',
            'eventoConfiguracion',
        ])->findOrFail($serieId);

        $lugares = Lugar::where('status', 'active')->get();

        $equiposIds = is_array($serie->equipos_ids) ? $serie->equipos_ids : [];
        $primerParticipante = Preinscripcion::whereIn('id', $equiposIds)->first();
        $esIndividual = $primerParticipante?->tipo_inscripcion === 'individual';

        $tablaPosiciones = $esIndividual ? collect() : $serie->tablaPosiciones;

        return view('fixture.ver_serie', compact('serie', 'lugares', 'esIndividual', 'tablaPosiciones'));
    }

    public function calendarioEvento($eventoId, Request $request)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);
        $fecha = $request->get('fecha');

        $query = Serie::where('evento_configuracion_id', $eventoId)
            ->with(['partidos.equipoLocal', 'partidos.equipoVisitante', 'partidos.lugar', 'disciplina']);

        if ($fecha) {
            $query->whereDate('created_at', date('Y-m-d', strtotime($fecha)));
        }

        $series = $query->get();

        return view('fixture.calendario', compact('evento', 'series'));
    }

    public function asignarLugar(Request $request, $partidoId)
    {
        $request->validate([
            'lugar_id' => 'nullable|integer|exists:lugares,id',
            'fecha' => 'nullable|date',
            'hora_inicio' => 'nullable|date_format:H:i',
        ]);

        $partido = Partido::findOrFail($partidoId);
        $partido->lugar_id = $request->lugar_id;
        $partido->fecha = $request->fecha;
        $partido->hora_inicio = $request->hora_inicio;
        $partido->save();

        return response()->json(['success' => true]);
    }

    public function imprimirFixture($eventoId, Request $request)
    {
        $evento = EventoConfiguracion::findOrFail($eventoId);
        $fecha = $request->get('fecha');

        $query = Serie::where('evento_configuracion_id', $eventoId)
            ->with(['partidos.equipoLocal', 'partidos.equipoVisitante', 'partidos.lugar', 'disciplina']);

        if ($fecha) {
            $query->whereDate('created_at', date('Y-m-d', strtotime($fecha)));
        }

        $series = $query->get();

        return view('fixture.imprimir', compact('evento', 'series'));
    }
}
