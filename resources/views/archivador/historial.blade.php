@extends('layouts.panel')

@section('title', 'Historial de cambios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">
        <i class="fas fa-history text-danger"></i>
        Historial de cambios
        <small class="text-muted fs-6">&mdash; {{ $preinscripcion->nombre_participante ?? $preinscripcion->nombre_equipo }}</small>
    </h3>
    <a href="{{ route('archivador.show', $preinscripcion->id) }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($historial->isEmpty())
<div class="text-center py-5">
    <i class="fas fa-history fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Sin historial registrado</h5>
</div>
@else
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Realizado por</th>
                    <th>Estado anterior</th>
                    <th>Estado nuevo</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $h)
                @php
                    $badges = [
                        'pendiente'  => 'bg-warning text-dark',
                        'habilitado' => 'bg-success',
                        'observado'  => 'bg-danger',
                    ];
                @endphp
                <tr>
                    <td>
                        <small class="text-muted">{{ $h->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        {{ $h->usuario->name ?? 'Sistema' }}
                        @if($h->usuario)
                        <br><small class="text-muted">{{ $h->usuario->email }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $badges[$h->estado_anterior] ?? 'bg-secondary' }}">
                            {{ ucfirst($h->estado_anterior) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $badges[$h->estado_nuevo] ?? 'bg-secondary' }}">
                            {{ ucfirst($h->estado_nuevo) }}
                        </span>
                    </td>
                    <td>
                        <small>{{ $h->motivo ?? '&mdash;' }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
