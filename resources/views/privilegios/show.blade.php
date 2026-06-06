@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Rol</h3>
                    <div class="card-tools">
                        <a href="{{ route('privilegios.edit', $rol->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('privilegios.index') }}" class="btn btn-secondary btn-sm">
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

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Permisos asignados:</strong>
                            <div class="mt-2">
                                @foreach($rol->permisos as $permiso)
                                    <span class="badge badge-info">{{ $permiso->nombre }}</span>
                                @endforeach
                                @if($rol->permisos->count() == 0)
                                    <span class="badge badge-secondary">Sin permisos asignados</span>
                                @endif
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