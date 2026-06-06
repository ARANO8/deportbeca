@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configurar Permisos para: {{ $rol->nombre }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('privilegios.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('privilegios.update', $rol->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Ver</th>
                                        <th>Crear</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modulos as $modulo)
                                    <tr>
                                        <td><strong>{{ ucfirst($modulo) }}</strong></td>
                                        <td>
                                            <input type="checkbox" name="permisos[{{ $modulo }}][ver]" {{ $permisos[$modulo]['ver'] ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="permisos[{{ $modulo }}][crear]" {{ $permisos[$modulo]['crear'] ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="permisos[{{ $modulo }}][editar]" {{ $permisos[$modulo]['editar'] ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="permisos[{{ $modulo }}][eliminar]" {{ $permisos[$modulo]['eliminar'] ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar Permisos</button>
                        <a href="{{ route('privilegios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection