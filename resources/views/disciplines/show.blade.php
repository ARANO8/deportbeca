@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-info-circle"></i> Detalles de la Disciplina
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{ route('disciplinas.edit', $discipline->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('disciplinas.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i> Regresar
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Código:</th>
                        <td>{{ $discipline->codigo ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $discipline->nombre }}</td>
                    </tr>
                    @if($discipline->disciplinaPadre)
                    <tr>
                        <th>Disciplina Padre:</th>
                        <td>{{ $discipline->disciplinaPadre->nombre }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $discipline->descripcion ?? 'Sin descripción' }}</td>
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
                        <th>Fecha de creación:</th>
                        <td>{{ $discipline->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Última actualización:</th>
                        <td>{{ $discipline->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Mapa de ubicación -->
        @if($discipline->tieneCoordenadas() || $discipline->ubicacion_mapa)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong><i class="fas fa-map-marker-alt"></i> Ubicacion:</strong>
                </div>
                @if($discipline->tieneCoordenadas())
                    @include('partials.map-display', ['id' => 'mapVerDisciplina', 'lat' => $discipline->latitud, 'lng' => $discipline->longitud])
                @else
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item"
                            src="{{ $discipline->ubicacion_mapa }}"
                            style="border:0; border-radius: 10px;"
                            allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Esta disciplina no tiene una ubicación de mapa configurada.
                </div>
            </div>
        </div>
        @endif
        
        <!-- Subdisciplinas -->
        @if($discipline->children->count() > 0)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-secondary">
                    <strong><i class="fas fa-sitemap"></i> Subdisciplinas:</strong>
                </div>
                <ul class="list-group">
                    @foreach($discipline->children as $sub)
                        <li class="list-group-item">
                            <i class="fas fa-angle-right"></i> {{ $sub->nombre }}
                            @if($sub->status == 'inactive')
                                <span class="badge badge-danger float-right">Inactivo</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection