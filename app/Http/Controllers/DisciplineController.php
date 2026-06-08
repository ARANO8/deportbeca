<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DisciplineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $disciplines = Discipline::with('disciplinaPadre');

        if ($search) {
            $disciplines = $disciplines->where('nombre', 'like', "%{$search}%")
                ->orWhere('codigo', 'like', "%{$search}%");
        }

        if ($status) {
            $disciplines = $disciplines->where('status', $status);
        }

        $disciplines = $disciplines->orderBy('parent_id')
            ->orderBy('nombre')
            ->paginate(10);

        return view('disciplines.index', compact('disciplines'));
    }

    public function create()
    {
        $disciplinasPadre = Discipline::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('nombre')
            ->get();

        return view('disciplines.create', compact('disciplinasPadre'));
    }

    public function store(Request $request)
    {
        $validated = $this->datosValidados($request);

        Discipline::create($validated);

        Session::flash('toastr_success', 'Disciplina creada correctamente.');

        return redirect()->route('disciplinas.index');
    }

    public function show($id)
    {
        $discipline = Discipline::with('disciplinaPadre', 'children')->findOrFail($id);

        return view('disciplines.show', compact('discipline'));
    }

    public function edit($id)
    {
        $discipline = Discipline::findOrFail($id);
        $disciplinasPadre = Discipline::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->where('status', 'active')
            ->orderBy('nombre')
            ->get();

        return view('disciplines.edit', compact('discipline', 'disciplinasPadre'));
    }

    public function update(Request $request, $id)
    {
        $discipline = Discipline::findOrFail($id);

        $validated = $this->datosValidados($request, (int) $id);

        $discipline->update($validated);

        Session::flash('toastr_success', 'Disciplina actualizada correctamente.');

        return redirect()->route('disciplinas.index');
    }

    /**
     * Reglas compartidas para crear/editar disciplinas, incluyendo los limites
     * de integrantes por modalidad (grupal/individual). Valida ademas que el
     * maximo no sea menor que el minimo dentro de cada modalidad.
     */
    private function datosValidados(Request $request, ?int $id = null): array
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50|unique:disciplines,codigo'.($id ? ','.$id : ''),
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:disciplines,id',
            'status' => 'required|in:active,inactive',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'min_integrantes_grupal' => 'nullable|integer|min:1|max:99',
            'max_integrantes_grupal' => 'nullable|integer|min:1|max:99',
            'min_integrantes_individual' => 'nullable|integer|min:1|max:99',
            'max_integrantes_individual' => 'nullable|integer|min:1|max:99',
        ]);

        $validator->after(function ($v) use ($request) {
            foreach (['grupal', 'individual'] as $mod) {
                $min = $request->input("min_integrantes_{$mod}");
                $max = $request->input("max_integrantes_{$mod}");
                if (! is_null($min) && ! is_null($max) && (int) $max < (int) $min) {
                    $v->errors()->add("max_integrantes_{$mod}", 'El maximo debe ser mayor o igual al minimo de esa modalidad.');
                }
            }
        });

        return $validator->validate();
    }

    public function destroy($id)
    {
        $discipline = Discipline::findOrFail($id);

        // Verificar si tiene subdisciplinas
        if ($discipline->children()->count() > 0) {
            Session::flash('toastr_error', 'No se puede eliminar una disciplina que tiene subdisciplinas asociadas.');

            return redirect()->route('disciplinas.index');
        }

        // Verificar si tiene eventos asociados
        if ($discipline->eventos()->exists()) {
            Session::flash('toastr_error', 'No se puede eliminar una disciplina que tiene eventos asociados.');

            return redirect()->route('disciplinas.index');
        }

        $discipline->delete();
        Session::flash('toastr_info', 'Disciplina eliminada correctamente.');

        return redirect()->route('disciplinas.index');
    }

    public function activo($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->update(['status' => 'active']);
        Session::flash('toastr_success', 'Disciplina activada.');

        return redirect()->route('disciplinas.index');
    }

    public function inactivo($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->update(['status' => 'inactive']);
        Session::flash('toastr_info', 'Disciplina desactivada.');

        return redirect()->route('disciplinas.index');
    }
}
