@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Rol</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.edit', $rol->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nombre:</strong> {{ $rol->nombre }}
                        </div>
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            @if($rol->status == 'active')
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Descripcion:</strong> {{ $rol->descripcion ?? 'Sin descripcion' }}
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <strong>Permisos asignados:</strong>
                            <div class="mt-2">
                                @php
                                    $modulos = ['usuarios', 'carreras', 'disciplinas', 'lugares', 'preinscripciones'];
                                    $permisosLista = [];
                                    foreach ($modulos as $modulo) {
                                        $permiso = \App\Models\RolModuloPermiso::where('rol_id', $rol->id)
                                            ->where('modulo', $modulo)
                                            ->first();
                                        
                                        $permisosTexto = [];
                                        if ($permiso->ver ?? false) $permisosTexto[] = 'Ver';
                                        if ($permiso->crear ?? false) $permisosTexto[] = 'Crear';
                                        if ($permiso->editar ?? false) $permisosTexto[] = 'Editar';
                                        if ($permiso->eliminar ?? false) $permisosTexto[] = 'Eliminar';
                                        
                                        if (count($permisosTexto) > 0) {
                                            $permisosLista[] = '<strong>' . ucfirst($modulo) . ':</strong> ' . implode(', ', $permisosTexto);
                                        } else {
                                            $permisosLista[] = '<strong>' . ucfirst($modulo) . ':</strong> Sin permisos';
                                        }
                                    }
                                @endphp
                                <ul>
                                    @foreach($permisosLista as $item)
                                        <li>{!! $item !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Fecha Creacion:</strong> {{ $rol->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ultima Actualizacion:</strong> {{ $rol->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection