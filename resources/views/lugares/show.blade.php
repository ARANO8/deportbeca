@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Lugar</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.lugares.edit', $lugar->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Codigo:</strong> {{ $lugar->codigo }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nombre:</strong> {{ $lugar->nombre }}
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Descripcion:</strong> {{ $lugar->descripcion ?? 'Sin descripcion' }}
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Direccion:</strong> {{ $lugar->direccion }}
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Estado:</strong>
                            @if($lugar->status == 'active')
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    @if($lugar->embed_mapa)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Mapa:</strong>
                            <div class="mt-2">
                                {{-- Solo se permite el iframe de Google Maps; cualquier otro contenido se escapa --}}
                                @php
                                    $mapaSanitizado = null;
                                    if (preg_match(
                                        '/^<iframe\s[^>]*src=["\']https:\/\/www\.google\.com\/maps\/embed[^"\']*["\'][^>]*><\/iframe>$/i',
                                        trim($lugar->embed_mapa)
                                    )) {
                                        $mapaSanitizado = $lugar->embed_mapa;
                                    }
                                @endphp

                                @if($mapaSanitizado)
                                    {!! $mapaSanitizado !!}
                                @else
                                    <div class="alert alert-warning py-1 small">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        El embed almacenado no es un iframe de Google Maps válido.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Fecha Creacion:</strong> {{ $lugar->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ultima Actualizacion:</strong> {{ $lugar->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection