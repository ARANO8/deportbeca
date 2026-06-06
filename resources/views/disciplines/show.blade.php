@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalle de Disciplina</h3>
                    <div class="card-tools">
                        <a href="{{ route('disciplines.edit', $discipline) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID:</th>
                                    <td>{{ $discipline->id }}</td>
                                </tr>
                                <tr>
                                    <th>Código:</th>
                                    <td>{{ $discipline->codigo }}</td>
                                </tr>
                                <tr>
                                    <th>Código Completo:</th>
                                    <td><code>{{ $discipline->codigo_completo }}</code></td>
                                </tr>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $discipline->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Descripción:</th>
                                    <td>{{ $discipline->descripcion ?: 'Sin descripción' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        @if($discipline->parent_id)
                                            <span class="badge badge-info">Subdisciplina</span>
                                            <br>
                                            <small>Disciplina Padre: <strong>{{ $discipline->parent->nombre }}</strong> [{{ $discipline->parent->codigo }}]</small>
                                        @else
                                            <span class="badge badge-primary">Disciplina Principal</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($discipline->status == 'active')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Creación:</th>
                                    <td>{{ $discipline->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">Subdisciplinas</h5>
                                </div>
                                <div class="card-body">
                                    @if($discipline->subDisciplines->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nombre</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($discipline->subDisciplines as $sub)
                                                    <tr>
                                                        <td>{{ $sub->codigo }}</td>
                                                        <td>{{ $sub->nombre }}</td>
                                                        <td>
                                                            @if($sub->status == 'active')
                                                                <span class="badge badge-success">Activo</span>
                                                            @else
                                                                <span class="badge badge-danger">Inactivo</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('disciplines.show', $sub) }}" class="btn btn-info btn-xs">Ver</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">Esta disciplina no tiene subdisciplinas registradas.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection