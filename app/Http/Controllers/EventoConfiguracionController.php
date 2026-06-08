<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\EventoConfiguracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EventoConfiguracionController extends Controller
{
    public function index()
    {
        // Eager load disciplines para evitar N+1 en la vista
        $configuraciones = EventoConfiguracion::with('disciplines')->get();

        return view('eventos.index', compact('configuraciones'));
    }

    /**
     * Vista informativa (solo lectura) de un evento configurado.
     * Accesible con permiso eventos,ver; no expone controles de configuracion.
     */
    public function show($tipoEvento)
    {
        $configuracion = EventoConfiguracion::with('disciplines')
            ->where('tipo_evento', $tipoEvento)
            ->firstOrFail();

        return view('eventos.show', compact('configuracion', 'tipoEvento'));
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

        // Disciplinas (hojas) que tienen rango oficial: pueden recibir un cupo por evento.
        $disciplinasConRango = collect();
        foreach ($disciplinas as $d) {
            $candidatas = $d->subDisciplines->count() > 0 ? $d->subDisciplines : collect([$d]);
            foreach ($candidatas as $c) {
                if ($c->tieneRango('grupal') || $c->tieneRango('individual')) {
                    $disciplinasConRango->push($c);
                }
            }
        }

        // Cupo (override) por disciplina ya guardado en el pivot de este evento.
        $cuposActuales = [];
        if ($configuracion->exists) {
            foreach ($configuracion->disciplines as $d) {
                $cuposActuales[$d->id] = [
                    'min_grupal' => $d->pivot->min_integrantes_grupal,
                    'max_grupal' => $d->pivot->max_integrantes_grupal,
                    'min_individual' => $d->pivot->min_integrantes_individual,
                    'max_individual' => $d->pivot->max_integrantes_individual,
                ];
            }
        }

        return view('eventos.edit', compact('configuracion', 'disciplinas', 'tipoEvento', 'disciplinasConRango', 'cuposActuales'));
    }

    public function update(Request $request, $tipoEvento)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'disciplinas_ids' => 'nullable|array',
            'disciplinas_ids.*' => 'integer|exists:disciplines,id',
            'max_integrantes_grupal' => 'integer|min:1|max:20',
            'min_integrantes_grupal' => 'integer|min:1',
            'cupos' => 'nullable|array',
            'cupos.*.min_grupal' => 'nullable|integer|min:1|max:99',
            'cupos.*.max_grupal' => 'nullable|integer|min:1|max:99',
            'cupos.*.min_individual' => 'nullable|integer|min:1|max:99',
            'cupos.*.max_individual' => 'nullable|integer|min:1|max:99',
        ]);

        $disciplinasIds = $request->input('disciplinas_ids', []);
        $cuposInput = $request->input('cupos', []);

        // El cupo por evento debe respetar el rango oficial de cada disciplina.
        $validator->after(function ($v) use ($disciplinasIds, $cuposInput) {
            $discs = Discipline::whereIn('id', $disciplinasIds)->get()->keyBy('id');
            foreach ($disciplinasIds as $did) {
                $disc = $discs->get($did);
                if (! $disc) {
                    continue;
                }
                $c = $cuposInput[$did] ?? [];
                foreach (['grupal', 'individual'] as $mod) {
                    $min = $c["min_{$mod}"] ?? null;
                    $max = $c["max_{$mod}"] ?? null;
                    if ($min === null && $max === null) {
                        continue;
                    }
                    if ($min !== null && $max !== null && (int) $max < (int) $min) {
                        $v->errors()->add("cupos.{$did}.max_{$mod}", "El maximo de {$disc->nombre} ({$mod}) debe ser mayor o igual al minimo.");
                    }
                    $oMin = $disc->{"min_integrantes_{$mod}"};
                    $oMax = $disc->{"max_integrantes_{$mod}"};
                    if ($oMin !== null && $min !== null && (int) $min < $oMin) {
                        $v->errors()->add("cupos.{$did}.min_{$mod}", "El minimo de {$disc->nombre} ({$mod}) no puede ser menor al oficial ({$oMin}).");
                    }
                    if ($oMax !== null && $max !== null && (int) $max > $oMax) {
                        $v->errors()->add("cupos.{$did}.max_{$mod}", "El maximo de {$disc->nombre} ({$mod}) no puede exceder el oficial ({$oMax}).");
                    }
                }
            }
        });

        $validator->validate();

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

        // Sincronizar disciplinas guardando el cupo (override) por disciplina en el pivot.
        // Valor vacio => null => se hereda el rango oficial de la disciplina.
        $syncData = [];
        foreach ($disciplinasIds as $did) {
            $c = $cuposInput[$did] ?? [];
            $syncData[$did] = [
                'min_integrantes_grupal' => $c['min_grupal'] ?? null,
                'max_integrantes_grupal' => $c['max_grupal'] ?? null,
                'min_integrantes_individual' => $c['min_individual'] ?? null,
                'max_integrantes_individual' => $c['max_individual'] ?? null,
            ];
        }
        $configuracion->disciplines()->sync($syncData);

        Session::flash('toastr_success', "Evento configurado. Codigo: {$configuracion->codigo_acceso}");

        return redirect()->route('eventos.index');
    }
}
