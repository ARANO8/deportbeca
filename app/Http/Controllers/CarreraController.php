<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Facultad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarreraController extends Controller
{
    public function index(Request $request)
    {
        $facultad_id = $request->get('facultad_id');

        $carreras = Carrera::when($facultad_id, function ($query) use ($facultad_id) {
            $query->where('facultad_id', $facultad_id);
        })->orderBy('id', 'desc')->paginate(10);

        $facultades = Facultad::active()->get();

        return view('carreras.index', compact('carreras', 'facultades', 'facultad_id'));
    }

    public function create()
    {
        $facultades = Facultad::active()->get();

        return view('carreras.create', compact('facultades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:carreras,codigo',
            'nombre' => 'required|string|max:150',
            'facultad_id' => 'required|exists:facultades,id',
            'status' => 'required|in:active,inactive',
        ]);

        Carrera::create($request->all());

        Session::flash('toastr_success', '✅ Carrera creada correctamente.');

        return redirect()->route('carreras.index');
    }

    public function show(Carrera $carrera)
    {
        return view('carreras.show', compact('carrera'));
    }

    public function edit(Carrera $carrera)
    {
        $facultades = Facultad::active()->get();

        return view('carreras.edit', compact('carrera', 'facultades'));
    }

    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:carreras,codigo,'.$carrera->id,
            'nombre' => 'required|string|max:150',
            'facultad_id' => 'required|exists:facultades,id',
            'status' => 'required|in:active,inactive',
        ]);

        $carrera->update($request->all());

        Session::flash('toastr_success', '✅ Carrera actualizada correctamente.');

        return redirect()->route('carreras.index');
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();

        Session::flash('toastr_info', '🗑️ Carrera eliminada correctamente.');

        return redirect()->route('carreras.index');
    }

    public function activo($id)
    {
        $carrera = Carrera::findOrFail($id);
        $carrera->update(['status' => 'active']);

        Session::flash('toastr_success', '🟢 Carrera activada correctamente.');

        return redirect()->route('carreras.index');
    }

    public function inactivo($id)
    {
        $carrera = Carrera::findOrFail($id);
        $carrera->update(['status' => 'inactive']);

        Session::flash('toastr_info', '🔴 Carrera desactivada correctamente.');

        return redirect()->route('carreras.index');
    }
}
