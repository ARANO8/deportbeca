<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $request->validate([
            'codigo' => 'nullable|string|max:50|unique:disciplines,codigo',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:disciplines,id',
            'status' => 'required|in:active,inactive',
            'ubicacion_mapa' => 'nullable|url',
        ]);

        Discipline::create($request->all());

        Session::flash('toastr_success', 'Disciplina creada correctamente.');
        return redirect()->route('disciplines.index');
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
        
        $request->validate([
            'codigo' => 'nullable|string|max:50|unique:disciplines,codigo,'.$id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:disciplines,id',
            'status' => 'required|in:active,inactive',
            'ubicacion_mapa' => 'nullable|url',
        ]);

        $discipline->update($request->all());

        Session::flash('toastr_success', 'Disciplina actualizada correctamente.');
        return redirect()->route('disciplines.index');
    }

    public function destroy($id)
    {
        $discipline = Discipline::findOrFail($id);
        
        // Verificar si tiene subdisciplinas
        if ($discipline->children()->count() > 0) {
            Session::flash('toastr_error', 'No se puede eliminar una disciplina que tiene subdisciplinas asociadas.');
            return redirect()->route('disciplines.index');
        }
        
        // Verificar si tiene eventos asociados
        if ($discipline->eventos()->exists()) {
            Session::flash('toastr_error', 'No se puede eliminar una disciplina que tiene eventos asociados.');
            return redirect()->route('disciplines.index');
        }
        
        $discipline->delete();
        Session::flash('toastr_info', 'Disciplina eliminada correctamente.');
        return redirect()->route('disciplines.index');
    }
    
    public function activo($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->update(['status' => 'active']);
        Session::flash('toastr_success', 'Disciplina activada.');
        return redirect()->route('disciplines.index');
    }
    
    public function inactivo($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->update(['status' => 'inactive']);
        Session::flash('toastr_info', 'Disciplina desactivada.');
        return redirect()->route('disciplines.index');
    }
}