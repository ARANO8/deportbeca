@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-list"></i> Lista de Disciplinas
                </h3>
            </div>
            <div class="col text-right">
                @puede('disciplinas','crear')
                <a href="{{ route('disciplinas.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nueva Disciplina
                </a>
                @endpuede
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form method="GET" action="{{ route('disciplinas.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por nombre o código..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('disciplinas.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </div>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Disciplina Padre</th>
                    <th>Mapa</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disciplines as $discipline)
                <tr>
                    <td>{{ $discipline->id }}</td>
                    <td>{{ $discipline->codigo ?? '-' }}</td>
                    <td>{{ $discipline->nombre }}</td>
                    <td>{{ $discipline->disciplinaPadre->nombre ?? '-' }}</td>
                    <td>
                        @if($discipline->tieneCoordenadas() || $discipline->ubicacion_mapa)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Configurado
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                <i class="fas fa-times-circle"></i> No configurado
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($discipline->status == 'active')
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>{{ $discipline->created_at->format('d/m/Y') }}</td>
                    <td>
                        @puede('disciplinas','ver')
                        <a href="{{ route('disciplinas.show', $discipline->id) }}" class="btn btn-sm btn-info" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endpuede
                        @puede('disciplinas','editar')
                        <a href="{{ route('disciplinas.edit', $discipline->id) }}" class="btn btn-sm btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($discipline->status == 'active')
                            <form action="{{ route('disciplinas.inactivo', $discipline->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Desactivar esta disciplina?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-secondary" title="Desactivar">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('disciplinas.activo', $discipline->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Activar esta disciplina?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Activar">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        @endpuede
                        @puede('disciplinas','eliminar')
                        <form action="{{ route('disciplinas.destroy', $discipline->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar esta disciplina?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endpuede
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No hay disciplinas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        {{ $disciplines->appends(request()->query())->links() }}
    </div>
</div>
@endsection