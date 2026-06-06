@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestión de Disciplinas</h3>
                    <div class="card-tools">
                        <a href="{{ route('disciplines.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva Disciplina
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtro por disciplina padre -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Filtrar por Disciplina Principal:</label>
                                <select name="parent_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">Todas las disciplinas principales</option>
                                    @if(isset($mainDisciplines) && $mainDisciplines->count() > 0)
                                        @foreach($mainDisciplines as $main)
                                            <option value="{{ $main->id }}" {{ $parent_id == $main->id ? 'selected' : '' }}>
                                                [{{ $main->codigo }}] {{ $main->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <a href="{{ route('disciplines.index') }}" class="btn btn-secondary form-control">Limpiar</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disciplines as $discipline)
                            <tr>
                                <td>{{ $discipline->id }}</td>
                                <td>{{ $discipline->codigo }}</td>
                                <td>{{ $discipline->nombre }}</td>
                                <td>
                                    @if($discipline->parent_id)
                                        <span class="badge badge-info">Subdisciplina</span>
                                        <br>
                                        <small>Discipina: {{ $discipline->parent->nombre ?? 'N/A' }}</small>
                                    @else
                                        <span class="badge badge-primary">Disciplina Principal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($discipline->status == 'active')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('disciplines.show', $discipline) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('disciplines.edit', $discipline) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($discipline->status == 'active')
                                            <form action="{{ route('disciplines.inactivo', $discipline->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Desactivar esta disciplina?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('disciplines.activo', $discipline->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Activar esta disciplina?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('disciplines.destroy', $discipline) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta disciplina?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay disciplinas registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        {{ $disciplines->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection