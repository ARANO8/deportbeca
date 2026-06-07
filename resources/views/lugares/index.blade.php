@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion de Lugares</h3>
                    <div class="card-tools">
                        @puede('lugares','crear')
                        <a href="{{ route('admin.lugares.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Lugar
                        </a>
                        @endpuede
                    </div>
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
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Direccion</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lugares as $lugar)
                            <tr>
                                <td>{{ $lugar->id }}</td>
                                <td>{{ $lugar->codigo }}</td>
                                <td>{{ $lugar->nombre }}</td>
                                <td>{{ $lugar->direccion }}</td>
                                <td>
                                    @if($lugar->status == 'active')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @puede('lugares','ver')
                                        <a href="{{ route('admin.lugares.show', $lugar->id) }}" class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endpuede
                                        @puede('lugares','editar')
                                        <a href="{{ route('admin.lugares.edit', $lugar->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($lugar->status == 'active')
                                            <form action="{{ route('admin.lugares.inactivo', $lugar->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Desactivar este lugar?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-secondary btn-sm" title="Desactivar"><i class="fas fa-ban"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.lugares.activo', $lugar->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Activar este lugar?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm" title="Activar"><i class="fas fa-check"></i></button>
                                            </form>
                                        @endif
                                        @endpuede
                                        @puede('lugares','eliminar')
                                        <form action="{{ route('admin.lugares.destroy', $lugar->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Eliminar este lugar?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endpuede
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay lugares registrados穷
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $lugares->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection