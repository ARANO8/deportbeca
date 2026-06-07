@extends('layouts.panel')

@section('title', 'Configurar Eventos')

@section('styles')
<style>
    .eventos-hero {
        background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%);
        border-radius: 20px;
        padding: 32px 36px;
        margin-bottom: 28px;
    }

    .eventos-hero h1 {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .eventos-hero p {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        margin: 0;
    }

    .evento-card {
        background: var(--umsa-surface);
        border-radius: 16px;
        border: 1px solid var(--umsa-border);
        box-shadow: var(--shadow);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.25s, transform 0.25s;
    }

    .evento-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .evento-card-header {
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--umsa-border);
    }

    .evento-card-header.activo {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .evento-card-header.inactivo {
        background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%);
    }

    .evento-card-header h4 {
        color: white;
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
    }

    .evento-status-badge {
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        background: rgba(255,255,255,0.18);
        color: white;
    }

    .evento-card-body {
        padding: 20px 22px;
        flex: 1;
    }

    .evento-card-body p {
        margin-bottom: 10px;
        font-size: 0.85rem;
        color: var(--umsa-text);
    }

    .evento-card-body strong {
        color: var(--umsa-text-secondary);
    }

    .codigo-badge {
        display: inline-block;
        background: var(--umsa-bg);
        border: 1px solid var(--umsa-border);
        border-radius: 6px;
        padding: 2px 10px;
        font-family: monospace;
        font-size: 0.85rem;
        color: var(--umsa-primary);
        font-weight: 700;
        letter-spacing: 1px;
    }

    .evento-card-footer {
        padding: 14px 22px;
        border-top: 1px solid var(--umsa-border);
    }

    .btn-configurar {
        background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%);
        color: white;
        border: none;
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: block;
        text-align: center;
        transition: opacity 0.2s, transform 0.2s;
    }

    .btn-configurar:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .info-box {
        background: var(--umsa-primary-light);
        border: 1px solid var(--umsa-border);
        border-left: 4px solid var(--umsa-primary);
        border-radius: 10px;
        padding: 16px 20px;
        margin-top: 20px;
        color: var(--umsa-text);
        font-size: 0.85rem;
    }

    .info-box strong { color: var(--umsa-primary); }

    .equipos-count span {
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* ---- DARK MODE ---- */
    [data-theme="dark"] .evento-card {
        background: var(--umsa-surface);
        border-color: var(--umsa-border);
        box-shadow: 0 4px 16px rgba(0,0,0,0.4);
    }

    [data-theme="dark"] .evento-card-header {
        border-bottom-color: var(--umsa-border);
    }

    [data-theme="dark"] .evento-card-body p { color: var(--umsa-text); }
    [data-theme="dark"] .evento-card-body strong { color: var(--umsa-text-secondary); }

    [data-theme="dark"] .codigo-badge {
        background: rgba(255,255,255,0.06);
        border-color: var(--umsa-border);
        color: var(--umsa-primary-light);
    }

    [data-theme="dark"] .evento-card-footer {
        border-top-color: var(--umsa-border);
    }

    [data-theme="dark"] .info-box {
        background: rgba(26,82,118,0.15);
        border-color: var(--umsa-border);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="eventos-hero">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h1><i class="fas fa-calendar-alt mr-3"></i>Eventos</h1>
                <p>Informacion de los eventos deportivos: estado, vigencia, disciplinas habilitadas e inscripciones.</p>
            </div>
            <div class="col-md-3 text-right">
                <i class="fas fa-trophy text-white" style="font-size:60px;opacity:0.15;"></i>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach(['intercarreras' => 'Intercarreras', 'olimpiadas' => 'Olimpiadas', 'interauxiliares' => 'Interauxiliares'] as $key => $label)
        @php
            $config  = $configuraciones->firstWhere('tipo_evento', $key);
            $activo  = $config && $config->activo && $config->estaVigente();
            $fueraFecha = $config && $config->activo && !$config->estaVigente();
            $disciplinas_count = $config ? $config->disciplines->count() : 0;
        @endphp
        <div class="col-md-4 mb-4">
            <div class="evento-card">
                <div class="evento-card-header {{ $activo ? 'activo' : 'inactivo' }}">
                    <h4><i class="fas fa-calendar-check mr-2"></i>{{ $label }}</h4>
                    @if($activo)
                        <span class="evento-status-badge">Activo</span>
                    @elseif($fueraFecha)
                        <span class="evento-status-badge" style="background:rgba(245,158,11,0.35);">Fuera de fecha</span>
                    @else
                        <span class="evento-status-badge" style="background:rgba(220,38,38,0.35);">Inactivo</span>
                    @endif
                </div>

                <div class="evento-card-body">
                    <p>
                        <strong>Codigo de acceso:</strong><br>
                        <span class="codigo-badge">{{ $config->codigo_acceso ?? 'No generado' }}</span>
                    </p>
                    <p><strong>Disciplinas habilitadas:</strong> {{ $disciplinas_count }}</p>
                    @if($config && $config->fecha_inicio)
                        <p>
                            <strong>Vigencia:</strong><br>
                            {{ $config->fecha_inicio->format('d/m/Y') }}
                            &rarr;
                            {{ $config->fecha_fin?->format('d/m/Y') ?? 'Sin fecha fin' }}
                        </p>
                    @endif
                    <p class="equipos-count">
                        <strong>Equipos:</strong>
                        <span class="text-success">H: {{ \App\Models\Preinscripcion::habilitados()->where('tipo_evento', $key)->count() }}</span>
                        &nbsp;|&nbsp;
                        <span class="text-warning">O: {{ \App\Models\Preinscripcion::observados()->where('tipo_evento', $key)->count() }}</span>
                        &nbsp;|&nbsp;
                        <span class="text-secondary">P: {{ \App\Models\Preinscripcion::pendientes()->where('tipo_evento', $key)->count() }}</span>
                    </p>
                </div>

                <div class="evento-card-footer">
                    @if($config)
                    <a href="{{ route('eventos.show', $key) }}" class="btn-configurar" style="background:var(--umsa-surface);color:var(--umsa-primary);border:1px solid var(--umsa-border);">
                        <i class="fas fa-eye mr-2"></i>Ver detalle
                    </a>
                    @endif
                    @puede('eventos','editar')
                    <a href="{{ route('eventos.edit', $key) }}" class="btn-configurar" style="margin-top:8px;">
                        <i class="fas fa-cog mr-2"></i>Configurar
                    </a>
                    @endpuede
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @puede('eventos','editar')
    <div class="info-box">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Como funciona:</strong>
        Configure cada evento, activelo y comparta el codigo de acceso con los representantes.
        Los representantes ingresan el codigo en la pagina web para inscribirse.
        Revise las inscripciones en el <a href="{{ route('archivador.index') }}" style="color:var(--umsa-primary);">Archivador</a>.
    </div>
    @endpuede

</div>
@endsection
