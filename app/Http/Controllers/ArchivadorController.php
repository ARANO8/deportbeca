<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Preinscripcion;
use App\Models\PreinscripcionHistorial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ArchivadorController extends Controller
{
    public function index(Request $request)
    {
        $tipoEvento = $request->get('tipo_evento', 'intercarreras');
        $disciplinaId = $request->get('disciplina_id');

        $habilitados = Preinscripcion::habilitados()->where('tipo_evento', $tipoEvento)
            ->when($disciplinaId, fn ($q) => $q->where('disciplina_id', $disciplinaId))
            ->with(['disciplina', 'facultad', 'carrera', 'integrantes'])
            ->get();

        $observados = Preinscripcion::observados()->where('tipo_evento', $tipoEvento)
            ->when($disciplinaId, fn ($q) => $q->where('disciplina_id', $disciplinaId))
            ->with(['disciplina', 'facultad', 'carrera', 'integrantes'])
            ->get();

        $pendientes = Preinscripcion::pendientes()->where('tipo_evento', $tipoEvento)
            ->when($disciplinaId, fn ($q) => $q->where('disciplina_id', $disciplinaId))
            ->with(['disciplina', 'facultad', 'carrera', 'integrantes'])
            ->get();

        $disciplinas = Discipline::whereNull('parent_id')->with('subDisciplines')->get();

        return view('archivador.index', compact(
            'habilitados', 'observados', 'pendientes', 'disciplinas', 'tipoEvento', 'disciplinaId'
        ));
    }

    public function show($id)
    {
        $preinscripcion = Preinscripcion::with(['disciplina', 'facultad', 'carrera', 'integrantes'])
            ->findOrFail($id);

        return view('archivador.show', compact('preinscripcion'));
    }

    public function descargarDocumentoIntegrante($id, $integranteId, $tipo)
    {
        $preinscripcion = Preinscripcion::findOrFail($id);
        $integrante = $preinscripcion->integrantes()->findOrFail($integranteId);

        $path = match ($tipo) {
            'ci' => $integrante->documento_ci_path,
            'seguro' => $integrante->documento_seguro_path,
            'matricula' => $integrante->documento_matricula_path,
            default => null
        };

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')->download($path);
    }

    public function habilitar($id)
    {
        try {
            $preinscripcion = Preinscripcion::findOrFail($id);
            $preinscripcion->update([
                'estado' => Preinscripcion::ESTADO_HABILITADO,
                'observaciones' => null,
            ]);

            PreinscripcionHistorial::create([
                'preinscripcion_id' => $preinscripcion->id,
                'user_id' => Auth::id(),
                'estado_anterior' => Preinscripcion::ESTADO_PENDIENTE,
                'estado_nuevo' => Preinscripcion::ESTADO_HABILITADO,
                'motivo' => null,
            ]);

            $this->enviarCorreo($preinscripcion, 'habilitado');

            return redirect()->route('archivador.index')->with('toastr_success', '✅ '.($preinscripcion->tipo_inscripcion == 'individual' ? 'Inscripción' : 'Equipo').' habilitado exitosamente');

        } catch (\Exception $e) {
            return redirect()->route('archivador.index')->with('toastr_error', 'Error al habilitar: '.$e->getMessage());
        }
    }

    public function observar(Request $request, $id)
    {
        try {
            $motivo = $request->input('motivo_observacion');

            if (! $motivo || strlen($motivo) < 10) {
                return redirect()->route('archivador.index')->with('toastr_error', 'El motivo debe tener al menos 10 caracteres');
            }

            $preinscripcion = Preinscripcion::findOrFail($id);
            $estadoAnterior = $preinscripcion->estado;
            $preinscripcion->update([
                'estado' => Preinscripcion::ESTADO_OBSERVADO,
                'observaciones' => $motivo,
            ]);

            PreinscripcionHistorial::create([
                'preinscripcion_id' => $preinscripcion->id,
                'user_id' => Auth::id(),
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => Preinscripcion::ESTADO_OBSERVADO,
                'motivo' => $motivo,
            ]);

            $this->enviarCorreo($preinscripcion, 'observado', $motivo);

            return redirect()->route('archivador.index')->with('toastr_info', '📋 '.($preinscripcion->tipo_inscripcion == 'individual' ? 'Inscripción' : 'Equipo').' marcado como observado');

        } catch (\Exception $e) {
            return redirect()->route('archivador.index')->with('toastr_error', 'Error al observar: '.$e->getMessage());
        }
    }

    public function revertirPendiente($id)
    {
        try {
            $preinscripcion = Preinscripcion::findOrFail($id);
            $estadoAnteriorRevertir = $preinscripcion->estado;
            $preinscripcion->update([
                'estado' => Preinscripcion::ESTADO_PENDIENTE,
                'observaciones' => null,
            ]);

            PreinscripcionHistorial::create([
                'preinscripcion_id' => $preinscripcion->id,
                'user_id' => Auth::id(),
                'estado_anterior' => $estadoAnteriorRevertir,
                'estado_nuevo' => Preinscripcion::ESTADO_PENDIENTE,
                'motivo' => 'Revertido a pendiente',
            ]);

            return redirect()->route('archivador.index')->with('toastr_success', "🔄 Equipo '{$preinscripcion->nombre_equipo}' regresado a pendiente");

        } catch (\Exception $e) {
            return redirect()->route('archivador.index')->with('toastr_error', 'Error al revertir: '.$e->getMessage());
        }
    }

    public function descargarDocumento($id, $tipo)
    {
        $preinscripcion = Preinscripcion::findOrFail($id);

        $campo = match ($tipo) {
            'ci' => 'documento_ci_path',
            'seguro' => 'documento_seguro_path',
            'matricula' => 'documento_matricula_path',
            'aval' => 'documento_aval_path',
            default => null
        };

        $nombreArchivo = $preinscripcion->$campo;

        if (! $nombreArchivo) {
            return redirect()->back()->with('toastr_error', 'Documento no subido');
        }

        // Buscar el archivo en las carpetas
        $carpetas = [
            'preinscripciones/documentos/',
            'preinscripciones/integrantes/',
            'preinscripciones/',
        ];

        $rutaEncontrada = null;
        foreach ($carpetas as $carpeta) {
            $rutaPrueba = $carpeta.basename($nombreArchivo);
            if (Storage::disk('public')->exists($rutaPrueba)) {
                $rutaEncontrada = $rutaPrueba;
                break;
            }
        }

        if ($rutaEncontrada && Storage::disk('public')->exists($rutaEncontrada)) {
            return Storage::disk('public')->download($rutaEncontrada);
        }

        return redirect()->back()->with('toastr_error', 'Documento no encontrado en el servidor');
    }

    public function verDocumento($id, $tipo)
    {
        $preinscripcion = Preinscripcion::findOrFail($id);

        $path = match ($tipo) {
            'ci' => $preinscripcion->documento_ci_path,
            'seguro' => $preinscripcion->documento_seguro_path,
            'matricula' => $preinscripcion->documento_matricula_path,
            'aval' => $preinscripcion->documento_aval_path,
            default => null
        };

        if ($path && Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/'.$path));
        }

        return redirect()->back()->with('toastr_error', 'Documento no encontrado');
    }

    public function generarCredencial($id)
    {
        $preinscripcion = Preinscripcion::with(['disciplina', 'facultad', 'carrera', 'integrantes'])
            ->findOrFail($id);

        if ($preinscripcion->estado !== Preinscripcion::ESTADO_HABILITADO) {
            return redirect()->back()->with('toastr_error', 'Solo se pueden generar credenciales de inscripciones habilitadas.');
        }

        $pdf = Pdf::loadView('exports.credencial_pdf', compact('preinscripcion'))
            ->setPaper([0, 0, 595.28, 283.46], 'landscape');

        $nombreArchivo = 'credencial_'.$preinscripcion->codigo_inscripcion.'.pdf';

        return $pdf->download($nombreArchivo);
    }

    public function historial($id)
    {
        $preinscripcion = Preinscripcion::with('historial.usuario')->findOrFail($id);
        $historial = $preinscripcion->historial;

        return view('archivador.historial', compact('preinscripcion', 'historial'));
    }

    private function enviarCorreo($preinscripcion, $tipo, $motivo = null)
    {
        try {
            if (empty($preinscripcion->representante_email)) {
                \Log::error('Email del representante no encontrado para ID: '.$preinscripcion->id);

                return;
            }

            $tipoInscripcion = $preinscripcion->tipo_inscripcion ?? 'individual';

            $data = [
                'nombre_capitan' => $preinscripcion->representante_nombre,
                'nombre_equipo' => $preinscripcion->nombre_equipo ?: 'Individual',
                'tipo_evento' => strtoupper($preinscripcion->tipo_evento),
                'tipo_inscripcion' => $tipoInscripcion,
                'disciplina' => $preinscripcion->disciplina->nombre ?? 'N/A',
                'codigo' => $preinscripcion->codigo_inscripcion,
                'motivo' => $motivo,
                'fecha' => now()->format('d/m/Y H:i'),
                'cantidad_integrantes' => $preinscripcion->cantidad_integrantes ?? 1,
            ];

            if ($tipo == 'habilitado') {
                $subject = $tipoInscripcion == 'individual'
                    ? '✅ Tu inscripción ha sido HABILITADA - '.strtoupper($preinscripcion->tipo_evento)
                    : '✅ Tu equipo ha sido HABILITADO - '.strtoupper($preinscripcion->tipo_evento);
                $view = 'emails.habilitado';
            } else {
                $subject = $tipoInscripcion == 'individual'
                    ? '📋 Tu inscripción tiene OBSERVACIONES - '.strtoupper($preinscripcion->tipo_evento)
                    : '📋 Tu equipo tiene OBSERVACIONES - '.strtoupper($preinscripcion->tipo_evento);
                $view = 'emails.observado';
            }

            Mail::send($view, $data, function ($message) use ($preinscripcion, $subject) {
                $message->to($preinscripcion->representante_email)
                    ->subject($subject);
            });

            \Log::info('Correo enviado a: '.$preinscripcion->representante_email.' - Tipo: '.$tipo);

        } catch (\Exception $e) {
            \Log::error('Error al enviar correo: '.$e->getMessage());
        }
    }
}
