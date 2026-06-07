@extends('layouts.panel')

@section('title', 'Detalle de Evento')

@section('styles')
<style>
    .ev-hero { background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%); border-radius: 20px; padding: 30px 34px; margin-bottom: 24px; color: #fff; }
    .ev-hero h1 { color: #fff; font-size: 1.6rem; font-weight: 700; margin: 0 0 6px; }
    .ev-status { display: inline-block; font-size: .72rem; padding: 4px 12px; border-radius: 20px; font-weight: 700; color: #fff; margin-left: 6px; }
    .ev-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 22px; }
    .ev-card { background: var(--umsa-surface); border: 1px solid var(--umsa-border); border-radius: 14px; padding: 16px 20px; box-shadow: var(--shadow); }
    .ev-card .lbl { font-size: .7rem; text-transform: uppercase; letter-spacing: .5px; color: var(--umsa-text-muted); font-weight: 700; margin-bottom: 6px; }
    .ev-card .val { font-size: 1.05rem; color: var(--umsa-text); font-weight: 600; }
    .ev-section { background: var(--umsa-surface); border: 1px solid var(--umsa-border); border-radius: 16px; padding: 20px 24px; margin-bottom: 20px; box-shadow: var(--shadow); }
    .ev-section h3 { font-size: 1.05rem; color: var(--umsa-primary); font-weight: 700; margin: 0 0 14px; }
    .ev-codigo { font-family: monospace; letter-spacing: 1px; color: var(--umsa-primary); }
    .ev-disc { display: inline-block; background: var(--umsa-primary-light); color: var(--umsa-primary); border: 1px solid var(--umsa-border); border-radius: 20px; padding: 4px 14px; margin: 0 4px 8px 0; font-size: .85rem; font-weight: 600; }
    .ev-btn { display: inline-block; padding: 9px 18px; border-radius: 10px; font-weight: 600; font-size: .85rem; text-decoration: none; }
    .ev-btn-back { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; }
    .ev-btn-cfg { background: #fff; color: var(--umsa-primary); margin-left: 6px; }
    .ev-btn-portal { background: var(--umsa-surface); border: 1px solid var(--umsa-border); color: var(--umsa-primary); }
</style>
@endsection

@section('content')
@php
    $labels = ['intercarreras' => 'Intercarreras', 'olimpiadas' => 'Olimpiadas', 'interauxiliares' => 'Interauxiliares'];
    $label = $labels[$tipoEvento] ?? ucfirst($tipoEvento);
    $activo = $configuracion->activo && $configuracion->estaVigente();
    $fueraFecha = $configuracion->activo && ! $configuracion->estaVigente();
    $h = \App\Models\Preinscripcion::habilitados()->where('tipo_evento', $tipoEvento)->count();
    $o = \App\Models\Preinscripcion::observados()->where('tipo_evento', $tipoEvento)->count();
    $p = \App\Models\Preinscripcion::pendientes()->where('tipo_evento', $tipoEvento)->count();
@endphp
<div class="container-fluid">

    <div class="ev-hero">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="fas fa-calendar-check mr-2"></i>{{ $configuracion->nombre ?? $label }}</h1>
                <p style="margin:0;opacity:.85;">
                    Evento {{ $label }}
                    @if($activo)
                        <span class="ev-status" style="background:rgba(16,185,129,.35);">Activo</span>
                    @elseif($fueraFecha)
                        <span class="ev-status" style="background:rgba(245,158,11,.4);">Fuera de fecha</span>
                    @else
                        <span class="ev-status" style="background:rgba(220,38,38,.4);">Inactivo</span>
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('eventos.index') }}" class="ev-btn ev-btn-back"><i class="fas fa-chevron-left mr-1"></i>Volver</a>
                @puede('eventos','editar')
                <a href="{{ route('eventos.edit', $tipoEvento) }}" class="ev-btn ev-btn-cfg"><i class="fas fa-cog mr-1"></i>Configurar</a>
                @endpuede
            </div>
        </div>
    </div>

    @if($configuracion->descripcion)
    <div class="ev-section"><p style="margin:0;color:var(--umsa-text);">{{ $configuracion->descripcion }}</p></div>
    @endif

    <div class="ev-grid">
        <div class="ev-card">
            <div class="lbl">Codigo de acceso</div>
            <div class="val ev-codigo">{{ $configuracion->codigo_acceso ?? 'No generado' }}</div>
        </div>
        <div class="ev-card">
            <div class="lbl">Vigencia</div>
            <div class="val">
                @if($configuracion->fecha_inicio)
                    {{ $configuracion->fecha_inicio->format('d/m/Y') }} &rarr; {{ $configuracion->fecha_fin?->format('d/m/Y') ?? 'Sin fin' }}
                @else
                    Sin fechas definidas
                @endif
            </div>
        </div>
        <div class="ev-card">
            <div class="lbl">Integrantes por equipo</div>
            <div class="val">{{ $configuracion->min_integrantes_grupal }} a {{ $configuracion->max_integrantes_grupal }}</div>
        </div>
        <div class="ev-card">
            <div class="lbl">Cupo de inscripciones</div>
            <div class="val">{{ $configuracion->max_inscripciones ?? 'Sin limite' }}</div>
        </div>
    </div>

    <div class="ev-section">
        <h3><i class="fas fa-futbol mr-2"></i>Disciplinas habilitadas ({{ $configuracion->disciplines->count() }})</h3>
        @forelse($configuracion->disciplines as $d)
            <span class="ev-disc">{{ $d->nombre }}</span>
        @empty
            <p style="margin:0;color:var(--umsa-text-muted);">No hay disciplinas habilitadas para este evento.</p>
        @endforelse
    </div>

    <div class="ev-section">
        <h3><i class="fas fa-users mr-2"></i>Inscripciones</h3>
        <div class="ev-grid" style="margin-bottom:0;">
            <div class="ev-card"><div class="lbl">Habilitadas</div><div class="val" style="color:#10b981;">{{ $h }}</div></div>
            <div class="ev-card"><div class="lbl">Observadas</div><div class="val" style="color:#f59e0b;">{{ $o }}</div></div>
            <div class="ev-card"><div class="lbl">Pendientes</div><div class="val" style="color:var(--umsa-text-muted);">{{ $p }}</div></div>
        </div>
    </div>

    <div class="text-right">
        <a href="{{ route('portal.evento', $configuracion->id) }}" target="_blank" class="ev-btn ev-btn-portal">
            <i class="fas fa-external-link-alt mr-1"></i>Ver resultados publicos
        </a>
    </div>

</div>
@endsection
