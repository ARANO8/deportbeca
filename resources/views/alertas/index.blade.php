@extends('layouts.panel')

@section('title', 'Mis Alertas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">
        <i class="fas fa-bell text-danger"></i> Mis Alertas
    </h3>
    @if($alertas->total() > 0)
    <form action="{{ route('alertas.marcar.todas') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-check-double"></i> Marcar todas como leidas
        </button>
    </form>
    @endif
</div>

@if($alertas->isEmpty())
<div class="text-center py-5">
    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No tienes alertas</h5>
</div>
@else
<div class="list-group">
    @foreach($alertas as $alerta)
    @php
        $iconos = [
            'success' => 'fas fa-check-circle text-success',
            'warning' => 'fas fa-exclamation-triangle text-warning',
            'danger'  => 'fas fa-times-circle text-danger',
            'info'    => 'fas fa-info-circle text-primary',
        ];
        $icono = $iconos[$alerta->tipo] ?? 'fas fa-bell text-secondary';
        $bg = $alerta->leida ? '' : 'list-group-item-light fw-semibold';
    @endphp
    <div class="list-group-item list-group-item-action {{ $bg }}">
        <div class="d-flex w-100 justify-content-between align-items-start">
            <div class="d-flex gap-3">
                <div class="mt-1">
                    <i class="{{ $icono }} fa-lg"></i>
                </div>
                <div>
                    <p class="mb-1">{{ $alerta->titulo }}</p>
                    <small class="text-muted">{{ $alerta->mensaje }}</small>
                    <br>
                    <small class="text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if(!$alerta->leida)
                <form action="{{ route('alertas.marcar.leida', $alerta) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success" title="Marcar como leida">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                @endif
                @if($alerta->url)
                <a href="{{ $alerta->url }}" class="btn btn-sm btn-outline-primary" title="Ver detalle">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                @endif
                <form action="{{ route('alertas.destroy', $alerta) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-3">
    {{ $alertas->links() }}
</div>
@endif
@endsection
