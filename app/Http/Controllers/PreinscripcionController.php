<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\EventoConfiguracion;
use App\Models\Facultad;
use App\Models\Preinscripcion;
use App\Models\PreinscripcionIntegrante;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PreinscripcionController extends Controller
{
    public function validarCodigo(Request $request)
    {
        $request->validate(['codigo_acceso' => 'required|string']);

        $evento = EventoConfiguracion::where('codigo_acceso', $request->codigo_acceso)
            ->where('activo', true)
            ->first();

        if (! $evento) {
            return response()->json(['success' => false, 'message' => '❌ Código inválido']);
        }

        if (! $evento->estaVigente()) {
            return response()->json(['success' => false, 'message' => 'El plazo de inscripcion para este evento ya finalizo.']);
        }

        session(['evento_activo_id' => $evento->id]);

        return response()->json([
            'success' => true,
            'evento' => [
                'id' => $evento->id,
                'tipo_evento' => $evento->tipo_evento,
                'nombre' => $evento->nombre,
                'min_integrantes' => $evento->min_integrantes_grupal,
                'max_integrantes' => $evento->max_integrantes_grupal,
            ],
        ]);
    }

    public function formularioModal(Request $request)
    {
        $tipoEvento = $request->tipo_evento;
        $evento = EventoConfiguracion::where('tipo_evento', $tipoEvento)
            ->where('activo', true)
            ->first();

        if (! $evento || ! $evento->estaVigente()) {
            return '<div class="alert alert-danger">Evento no disponible</div>';
        }

        $disciplinas = $evento->disciplinasPermitidas();
        $facultades = Facultad::active()->get();
        $carreras = Carrera::active()->get();

        // Rango efectivo de integrantes por disciplina y modalidad (para el JS del modal)
        $rangos = [];
        foreach ($disciplinas as $d) {
            $rangos[$d->id] = [
                'grupal' => $evento->rangoIntegrantes($d, 'grupal'),
                'individual' => $evento->rangoIntegrantes($d, 'individual'),
            ];
        }

        return view('preinscripcion.modal_form', compact('evento', 'disciplinas', 'facultades', 'carreras', 'rangos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_inscripcion' => 'required|in:individual,grupal',
            'disciplina_id' => 'required|integer|exists:disciplines,id',
            'representante_nombre' => 'required|string|max:255',
            'representante_ci' => 'required|string|max:50',
            'representante_email' => 'required|email:rfc,strict|max:255',
            'representante_telefono' => 'required|string|max:30',
            'nombre_equipo' => 'nullable|string|max:255',
            'cantidad_integrantes' => 'nullable|integer|min:1',
            'documento_aval' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documento_ci_capitan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documento_seguro_capitan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documento_matricula_capitan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $evento = EventoConfiguracion::find(session('evento_activo_id'));

        if (! $evento || ! $evento->estaVigente()) {
            return response()->json(['success' => false, 'message' => 'Evento no disponible'], 400);
        }

        $tipoEvento = $evento->tipo_evento;
        $tipoInscripcion = $request->tipo_inscripcion;

        // La disciplina debe estar habilitada para el evento
        $disciplina = $evento->disciplines()->where('disciplines.id', $request->disciplina_id)->first();
        if (! $disciplina) {
            return response()->json(['success' => false, 'message' => 'La disciplina no esta habilitada para este evento.'], 422);
        }

        // La modalidad elegida debe estar permitida por la disciplina
        $rango = $evento->rangoIntegrantes($disciplina, $tipoInscripcion);
        if (! $rango['permite']) {
            return response()->json(['success' => false, 'message' => 'La disciplina '.$disciplina->nombre.' no admite la modalidad '.$tipoInscripcion.'.'], 422);
        }

        // La cantidad de participantes (principal + integrantes) debe caer en el rango
        $integrantesInput = collect($request->integrantes ?? [])
            ->filter(fn ($it) => is_array($it) && ! empty($it['nombre']))
            ->values();
        $personas = 1 + $integrantesInput->count();

        if ($personas < $rango['min'] || $personas > $rango['max']) {
            return response()->json([
                'success' => false,
                'message' => "Para {$disciplina->nombre} en modalidad {$tipoInscripcion} debe registrar entre {$rango['min']} y {$rango['max']} participantes (incluido el principal). Enviaste {$personas}.",
            ], 422);
        }

        // Verificar limite de inscripciones del evento
        if ($evento->max_inscripciones !== null) {
            $totalActual = Preinscripcion::where('tipo_evento', $evento->tipo_evento)
                ->whereIn('estado', [
                    Preinscripcion::ESTADO_PENDIENTE,
                    Preinscripcion::ESTADO_HABILITADO,
                ])
                ->count();

            if ($totalActual >= $evento->max_inscripciones) {
                return response()->json([
                    'success' => false,
                    'message' => 'El evento ha alcanzado el limite maximo de inscripciones ('.$evento->max_inscripciones.').',
                ], 422);
            }
        }

        DB::beginTransaction();

        try {

            // ========== SUBIR ARCHIVOS ==========
            // SEC-06: los documentos se guardan en disco 'local' (privado, fuera del webroot)
            // La descarga se realiza únicamente a través de la ruta autenticada /ver-documento
            $files = [];

            if ($request->hasFile('documento_aval')) {
                $files['documento_aval_path'] = $request->file('documento_aval')
                    ->store('preinscripciones/documentos', 'local');
            }

            if ($request->hasFile('documento_ci_capitan')) {
                $files['documento_ci_path'] = $request->file('documento_ci_capitan')
                    ->store('preinscripciones/integrantes', 'local');
            }

            if ($request->hasFile('documento_seguro_capitan')) {
                $files['documento_seguro_path'] = $request->file('documento_seguro_capitan')
                    ->store('preinscripciones/integrantes', 'local');
            }

            if ($request->hasFile('documento_matricula_capitan')) {
                $files['documento_matricula_path'] = $request->file('documento_matricula_capitan')
                    ->store('preinscripciones/integrantes', 'local');
            }

            // ========== DATOS BÁSICOS ==========
            $data = [
                'tipo_evento' => $evento->tipo_evento,
                'tipo_inscripcion' => $request->tipo_inscripcion,
                'disciplina_id' => $request->disciplina_id,
                'nombre_equipo' => $request->nombre_equipo ?? null,
                'cantidad_integrantes' => $personas,
                'representante_nombre' => $request->representante_nombre,
                'representante_ci' => $request->representante_ci,
                'representante_email' => $request->representante_email,
                'representante_telefono' => $request->representante_telefono,
            ];

            $data = array_merge($data, $files);

            // ========== ASIGNAR FACULTAD / CARRERA ==========
            // La inscripcion pertenece a una sola facultad (olimpiadas) o carrera
            // (intercarreras), sin importar la modalidad.
            if ($tipoEvento === 'olimpiadas') {
                $data['facultad_id'] = $request->facultad_id;
            } elseif ($tipoEvento === 'intercarreras') {
                $data['carrera_id'] = $request->carrera_id;
            }

            // ========== CREAR PREINSCRIPCIÓN ==========
            $preinscripcion = Preinscripcion::create($data);

            // ========== GUARDAR CAPITÁN COMO INTEGRANTE ==========
            PreinscripcionIntegrante::create([
                'preinscripcion_id' => $preinscripcion->id,
                'nombre' => $request->representante_nombre,
                'ci' => $request->representante_ci,
                'es_capitan' => true,
                'documento_ci_path' => $files['documento_ci_path'] ?? null,
                'documento_seguro_path' => $files['documento_seguro_path'] ?? null,
                'documento_matricula_path' => $files['documento_matricula_path'] ?? null,
            ]);

            // ========== GUARDAR PARTICIPANTES ADICIONALES (AMBAS MODALIDADES) ==========
            foreach ($integrantesInput as $integrante) {
                $integranteFiles = [];

                if (isset($integrante['documento_ci']) && $integrante['documento_ci'] instanceof UploadedFile) {
                    $integranteFiles['documento_ci_path'] = $integrante['documento_ci']->store('preinscripciones/integrantes', 'local');
                }
                if (isset($integrante['documento_seguro']) && $integrante['documento_seguro'] instanceof UploadedFile) {
                    $integranteFiles['documento_seguro_path'] = $integrante['documento_seguro']->store('preinscripciones/integrantes', 'local');
                }
                if (isset($integrante['documento_matricula']) && $integrante['documento_matricula'] instanceof UploadedFile) {
                    $integranteFiles['documento_matricula_path'] = $integrante['documento_matricula']->store('preinscripciones/integrantes', 'local');
                }

                PreinscripcionIntegrante::create([
                    'preinscripcion_id' => $preinscripcion->id,
                    'nombre' => $integrante['nombre'],
                    'ci' => $integrante['ci'] ?? null,
                    'es_capitan' => false,
                    'documento_ci_path' => $integranteFiles['documento_ci_path'] ?? null,
                    'documento_seguro_path' => $integranteFiles['documento_seguro_path'] ?? null,
                    'documento_matricula_path' => $integranteFiles['documento_matricula_path'] ?? null,
                ]);
            }

            DB::commit();
            session()->forget('evento_activo_id');

            return response()->json([
                'success' => true,
                'message' => 'Pre-inscripción exitosa',
                'codigo' => $preinscripcion->codigo_inscripcion,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function verificarEstado($codigo = null)
    {
        $inscripcion = null;

        if ($codigo) {
            $inscripcion = Preinscripcion::with(['disciplina', 'facultad', 'carrera'])
                ->where('codigo_inscripcion', $codigo)
                ->first();

            // Si el cliente pide JSON (AJAX / API) responder igual que antes
            if (request()->expectsJson()) {
                if (! $inscripcion) {
                    return response()->json(['success' => false, 'message' => 'Código no encontrado']);
                }

                return response()->json(['success' => true, 'data' => $inscripcion]);
            }
        }

        return view('preinscripcion.verificar', compact('inscripcion', 'codigo'));
    }
}
