@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion de Roles</h3>
                    <div class="card-tools">
                        @puede('roles','crear')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Rol
                        </a>
                        @endpuede
                    </div>
                </div>
                <div class="card-body">
                    @if(session('toastr_success'))
                        <div class="alert alert-success">{{ session('toastr_success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $rol)
                            <tr>
                                <td>{{ $rol->id }}</td>
                                <td>{{ $rol->nombre }}</td>
                                <td>{{ $rol->descripcion ?? 'Sin descripcion' }}</td>
                                <td>
                                    @if($rol->status == 'active')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @puede('roles','ver')
                                        <a href="{{ route('roles.show', $rol->id) }}" class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endpuede
                                        @puede('roles','editar')
                                        <a href="{{ route('roles.edit', $rol->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($rol->status == 'active')
                                            <form action="{{ route('roles.inactivo', $rol->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Desactivar este rol?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-secondary btn-sm" title="Desactivar"><i class="fas fa-ban"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('roles.activo', $rol->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Activar este rol?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm" title="Activar"><i class="fas fa-check"></i></button>
                                            </form>
                                        @endif
                                        @endpuede
                                        @puede('roles','eliminar')
                                        <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('Eliminar este rol?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endpuede
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay roles registrados
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection