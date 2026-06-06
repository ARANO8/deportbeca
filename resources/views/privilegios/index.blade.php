@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion de Privilegios</h3>
                </div>
                <div class="card-body">
                    @if(session('toastr_success'))
                        <div class="alert alert-success">{{ session('toastr_success') }}</div>
                    @endif
                    @if(session('toastr_error'))
                        <div class="alert alert-danger">{{ session('toastr_error') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $rol)
                            <tr>
                                <td>{{ $rol->id }}</td>
                                <td>{{ $rol->nombre }}</td>
                                <td>
                                    @if($rol->status == 'active')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('roles.show', $rol->id) }}" class="btn btn-info btn-sm" title="Ver Rol">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('privilegios.edit', $rol->id) }}" class="btn btn-warning btn-sm" title="Configurar Permisos">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                        @if($rol->status == 'active')
                                            <form action="{{ route('roles.inactivo', $rol->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Desactivar este rol?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-secondary btn-sm" title="Desactivar Rol"><i class="fas fa-ban"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('roles.activo', $rol->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Activar este rol?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm" title="Activar Rol"><i class="fas fa-check"></i></button>
                                            </form>
                                        @endif
                                        <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar Rol" onclick="return confirm('¿Eliminar este rol?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay roles registrados
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection