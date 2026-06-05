<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\RolModuloPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrivilegioController extends Controller
{
    public function index()
    {
        $roles = Rol::where('status', 'active')->orderBy('nombre')->get();
        $modulos = ['usuarios', 'carreras', 'disciplinas', 'lugares', 'preinscripciones', 'fixture', 'calificaciones', 'eventos', 'roles', 'privilegios'];

        return view('privilegios.index', compact('roles', 'modulos'));
    }

    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        $modulos = ['usuarios', 'carreras', 'disciplinas', 'lugares', 'preinscripciones', 'fixture', 'calificaciones', 'eventos', 'roles', 'privilegios'];
        $permisos = [];

        foreach ($modulos as $modulo) {
            $permiso = RolModuloPermiso::where('rol_id', $rol->id)
                ->where('modulo', $modulo)
                ->first();

            $permisos[$modulo] = [
                'ver' => $permiso->ver ?? false,
                'crear' => $permiso->crear ?? false,
                'editar' => $permiso->editar ?? false,
                'eliminar' => $permiso->eliminar ?? false,
            ];
        }

        return view('privilegios.edit', compact('rol', 'modulos', 'permisos'));
    }

    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $modulos = ['usuarios', 'carreras', 'disciplinas', 'lugares', 'preinscripciones', 'fixture', 'calificaciones', 'eventos', 'roles', 'privilegios'];

        foreach ($modulos as $modulo) {
            RolModuloPermiso::updateOrCreate(
                [
                    'rol_id' => $rol->id,
                    'modulo' => $modulo,
                ],
                [
                    'ver' => $request->has("permisos.{$modulo}.ver"),
                    'crear' => $request->has("permisos.{$modulo}.crear"),
                    'editar' => $request->has("permisos.{$modulo}.editar"),
                    'eliminar' => $request->has("permisos.{$modulo}.eliminar"),
                ]
            );
        }

        Session::flash('toastr_success', "Permisos actualizados para el rol {$rol->nombre}");

        return redirect()->route('privilegios.index');
    }
}
