@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Rol</h3>
                    <div class="card-tools">
                        @puede('roles','editar')
                        <a href="{{ route('roles.edit', $rol->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @endpuede

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
                                @if($rol->es_super_admin)
                                    <div class="alert alert-info py-2">
                                        <i class="fas fa-crown"></i> Super administrador: acceso total a todos los modulos.
                                    </div>
                                @endif
                                @php
                                    $etiquetas = ['ver' => 'Ver', 'crear' => 'Crear', 'editar' => 'Editar', 'eliminar' => 'Eliminar'];
                                @endphp
                                <ul>
                                    @forelse($rol->permisosModulo as $permiso)
                                        @php
                                            $acciones = [];
                                            foreach ($etiquetas as $col => $label) {
                                                if ($permiso->$col) {
                                                    $acciones[] = $label;
                                                }
                                            }
                                        @endphp
                                        <li>
                                            <strong>{{ ucfirst($permiso->modulo) }}:</strong>
                                            {{ count($acciones) ? implode(', ', $acciones) : 'Sin permisos' }}
                                        </li>
                                    @empty
                                        <li class="text-muted">Este rol aun no tiene permisos configurados. Asignalos desde Privilegios.</li>
                                    @endforelse
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
