<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::orderBy('nombre')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Rol::create($validated);

        Session::flash('toastr_success', 'Rol creado correctamente.');

        return redirect()->route('roles.index');
    }

    public function show($id)
    {
        $rol = Rol::findOrFail($id);

        return view('roles.show', compact('rol'));
    }

    public function edit($id)
    {
        $rol = Rol::findOrFail($id);

        return view('roles.edit', compact('rol'));
    }

    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre,'.$id,
            'descripcion' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $rol->update($validated);

        Session::flash('toastr_success', 'Rol actualizado correctamente.');

        return redirect()->route('roles.index');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);

        if ($rol->usuarios()->count() > 0) {
            Session::flash('toastr_error', 'No se puede eliminar un rol que tiene usuarios asignados.');

            return redirect()->route('roles.index');
        }

        $rol->delete();
        Session::flash('toastr_info', 'Rol eliminado correctamente.');

        return redirect()->route('roles.index');
    }

    public function activo($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update(['status' => 'active']);
        Session::flash('toastr_success', 'Rol activado.');

        return redirect()->route('roles.index');
    }

    public function inactivo($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update(['status' => 'inactive']);
        Session::flash('toastr_info', 'Rol desactivado.');

        return redirect()->route('roles.index');
    }
}
