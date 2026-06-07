<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::orderBy('codigo')->paginate(10);

        return view('lugares.index', compact('lugares'));
    }

    public function create()
    {
        return view('lugares.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:lugares,codigo',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'direccion' => 'required|string|max:255',
            'embed_mapa' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Lugar::create($validated);

        Session::flash('toastr_success', 'Lugar creado correctamente.');

        return redirect()->route('admin.lugares.index');
    }

    public function show($id)
    {
        $lugar = Lugar::findOrFail($id);

        return view('lugares.show', compact('lugar'));
    }

    public function edit($id)
    {
        $lugar = Lugar::findOrFail($id);

        return view('lugares.edit', compact('lugar'));
    }

    public function update(Request $request, $id)
    {
        $lugar = Lugar::findOrFail($id);

        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:lugares,codigo,'.$id,
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'direccion' => 'required|string|max:255',
            'embed_mapa' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $lugar->update($validated);

        Session::flash('toastr_success', 'Lugar actualizado correctamente.');

        return redirect()->route('admin.lugares.index');
    }

    public function destroy($id)
    {
        $lugar = Lugar::findOrFail($id);
        $lugar->delete();

        Session::flash('toastr_info', 'Lugar eliminado correctamente.');

        return redirect()->route('admin.lugares.index');
    }

    public function activo($id)
    {
        $lugar = Lugar::findOrFail($id);
        $lugar->update(['status' => 'active']);
        Session::flash('toastr_success', 'Lugar activado.');

        return redirect()->route('admin.lugares.index');
    }

    public function inactivo($id)
    {
        $lugar = Lugar::findOrFail($id);
        $lugar->update(['status' => 'inactive']);
        Session::flash('toastr_info', 'Lugar desactivado.');

        return redirect()->route('admin.lugares.index');
    }
}
