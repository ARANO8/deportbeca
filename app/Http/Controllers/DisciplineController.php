<?php

// ✅ ESTO DEBE SER LO PRIMERO, NI ESPACIOS, NI LÍNEAS EN BLANCO

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DisciplineController extends Controller
{
    public function index(Request $request)
    {
        $parent_id = $request->get('parent_id');

        // Obtener disciplinas según el filtro
        $disciplines = Discipline::when($parent_id, function ($query) use ($parent_id) {
            $query->where('parent_id', $parent_id);
        })->when(! $parent_id, function ($query) {
            $query->whereNull('parent_id'); // Solo disciplinas principales
        })->orderBy('id', 'desc')->paginate(10);

        // ✅ Siempre obtener las disciplinas principales para el filtro (sin importar si hay filtro o no)
        $mainDisciplines = Discipline::mainDisciplines()->active()->get();

        return view('disciplines.index', compact('disciplines', 'mainDisciplines', 'parent_id'));
    }

    public function create()
    {
        $mainDisciplines = Discipline::mainDisciplines()->active()->get();

        return view('disciplines.create', compact('mainDisciplines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:disciplines,codigo',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:disciplines,id',
            'status' => 'required|in:active,inactive',
        ]);

        Discipline::create($request->all());

        $tipo = $request->parent_id ? 'Subdisciplina' : 'Disciplina';
        Session::flash('toastr_success', "✅ {$tipo} creada correctamente.");

        return redirect()->route('disciplines.index');
    }

    public function show(Discipline $discipline)
    {
        // Cargar subdisciplinas y la disciplina padre si existe
        $discipline->load('subDisciplines', 'disciplinaPadre');

        return view('disciplines.show', compact('discipline'));
    }

    public function edit(Discipline $discipline)
    {
        $mainDisciplines = Discipline::mainDisciplines()->active()
            ->where('id', '!=', $discipline->id) // No puede ser su propio padre
            ->get();

        return view('disciplines.edit', compact('discipline', 'mainDisciplines'));
    }

    public function update(Request $request, Discipline $discipline)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:disciplines,codigo,'.$discipline->id,
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:disciplines,id|different:id',
            'status' => 'required|in:active,inactive',
        ]);

        // Validar que no cree un ciclo
        if ($request->parent_id && $discipline->subDisciplines()->count() > 0) {
            Session::flash('toastr_error', '⚠️ No se puede convertir una disciplina que tiene subdisciplinas en subdisciplina.');

            return redirect()->back()->withInput();
        }

        $discipline->update($request->all());

        $tipo = $discipline->parent_id ? 'Subdisciplina' : 'Disciplina';
        Session::flash('toastr_success', "✅ {$tipo} actualizada correctamente.");

        return redirect()->route('disciplines.index');
    }

    public function destroy(Discipline $discipline)
    {
        // Verificar si tiene subdisciplinas
        if ($discipline->subDisciplines()->count() > 0) {
            Session::flash('toastr_error', '⚠️ No se puede eliminar una disciplina que tiene subdisciplinas. Elimine primero las subdisciplinas.');

            return redirect()->route('disciplines.index');
        }

        $tipo = $discipline->parent_id ? 'Subdisciplina' : 'Disciplina';
        $discipline->delete();

        Session::flash('toastr_info', "🗑️ {$tipo} eliminada correctamente.");

        return redirect()->route('disciplines.index');
    }

    public function activo($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->update(['status' => 'active']);

        $tipo = $discipline->parent_id ? 'Subdisciplina' : 'Disciplina';
        Session::flash('toastr_success', "🟢 {$tipo} activada correctamente.");

        return redirect()->route('disciplines.index');
    }

    public function inactivo($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->update(['status' => 'inactive']);

        $tipo = $discipline->parent_id ? 'Subdisciplina' : 'Disciplina';
        Session::flash('toastr_info', "🔴 {$tipo} desactivada correctamente.");

        return redirect()->route('disciplines.index');
    }
}
