<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\EventoConfiguracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EventoConfiguracionController extends Controller
{
    public function index()
    {
        // Eager load disciplines para evitar N+1 en la vista
        $configuraciones = EventoConfiguracion::with('disciplines')->get();

        return view('eventos.index', compact('configuraciones'));
    }

    public function edit($tipoEvento)
    {
        // Eager load disciplines para pre-seleccionar los checkboxes correctamente
        $configuracion = EventoConfiguracion::with('disciplines')
            ->firstOrNew(['tipo_evento' => $tipoEvento]);

        $disciplinas = Discipline::whereNull('parent_id')
            ->with('subDisciplines')
            ->where('status', 'active')
            ->get();

        return view('eventos.edit', compact('configuracion', 'disciplinas', 'tipoEvento'));
    }

    public function update(Request $request, $tipoEvento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'disciplinas_ids' => 'nullable|array',
            'disciplinas_ids.*' => 'integer|exists:disciplines,id',
            'max_integrantes_grupal' => 'integer|min:1|max:20',
            'min_integrantes_grupal' => 'integer|min:1',
        ]);

        // Guardar configuracion sin disciplinas_ids (ya no es columna directa)
        $configuracion = EventoConfiguracion::updateOrCreate(
            ['tipo_evento' => $tipoEvento],
            [
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'activo' => $request->has('activo'),
                'codigo_acceso' => $request->codigo_acceso
                    ?? EventoConfiguracion::generateCodigoAcceso($tipoEvento),
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'max_integrantes_grupal' => $request->max_integrantes_grupal ?? 8,
                'min_integrantes_grupal' => $request->min_integrantes_grupal ?? 4,
            ]
        );

        // Sincronizar disciplinas via tabla junction (sync elimina las que no estan y agrega las nuevas)
        $configuracion->disciplines()->sync($request->disciplinas_ids ?? []);

        Session::flash('toastr_success', "Evento configurado. Codigo: {$configuracion->codigo_acceso}");

        return redirect()->route('eventos.index');
    }
}
