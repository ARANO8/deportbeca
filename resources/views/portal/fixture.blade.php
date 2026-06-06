@extends('layouts.portal')

@section('title', $evento->nombre . ' — Fixture')

@section('styles')
<style>
    .fixture-hero {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .fixture-hero h1 { color: white; font-size: 1.4rem; font-weight: 800; margin: 0 0 4px; }
    .fixture-hero p  { color: rgba(255,255,255,0.85); font-size: 0.8rem; margin: 0; }
    .btn-h {
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 600;
        text-decoration: none;
        color: white;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }
    .btn-h:hover { background: rgba(255,255,255,0.28); color: white; }

    .serie-section {
        margin-bottom: 36px;
    }
    .serie-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #334155;
    }
    .serie-title h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }
    .serie-title h3 i { color: #dc2626; }
    .serie-title a {
        font-size: 0.74rem;
        color: #dc2626;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .serie-title a:hover { color: #f87171; }

    .jornada-block {
        margin-bottom: 18px;
    }
    .jornada-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 8px;
        padding-left: 4px;
    }

    .partidos-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .partido-row {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        transition: border-color 0.2s;
    }
    .partido-row:hover { border-color: #475569; }
    .partido-row.finalizado { border-left: 3px solid #10b981; }
    .partido-row.pendiente  { border-left: 3px solid #334155; }

    .partido-equipos {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .equipo-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #e2e8f0;
        flex: 1;
        text-align: right;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .equipo-name.visitante { text-align: left; }

    .marcador {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .gol-box {
        background: #0f172a;
        border: 1px solid #475569;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        color: #f1f5f9;
    }

    .vs-sep {
        color: #475569;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .partido-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .meta-fecha {
        font-size: 0.72rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .meta-lugar {
        font-size: 0.7rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .estado-chip {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .chip-fin  { background: rgba(16,185,129,0.15); color: #10b981; }
    .chip-pend { background: #334155; color: #64748b; }
    .chip-prog { background: rgba(245,158,11,0.15); color: #f59e0b; }

    .empty-fixture {
        text-align: center;
        padding: 32px;
        color: #475569;
        font-size: 0.82rem;
        background: #1e293b;
        border: 1px dashed #334155;
        border-radius: 12px;
    }
    .empty-fixture i { font-size: 1.8rem; margin-bottom: 8px; display: block; opacity: 0.4; }

    @media (max-width: 640px) {
        .partido-meta { display: none; }
        .equipo-name  { font-size: 0.76rem; }
        .gol-box      { width: 30px; height: 30px; font-size: 0.9rem; }
    }
</style>
@endsection

@section('content')

<!-- Hero -->
<div class="fixture-hero">
    <div>
        <h1><i class="fas fa-calendar-week mr-2"></i>Fixture — {{ $evento->nombre }}</h1>
        <p>
            <i class="fas fa-tag mr-1"></i> {{ ucfirst($evento->tipo_evento) }}
        </p>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('portal.evento', $evento->id) }}" class="btn-h">
            <i class="fas fa-table"></i> Ver Posiciones
        </a>
        <a href="{{ route('portal.index') }}" class="btn-h">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

@if($series->count() > 0)
    @foreach($series as $serie)
    @php $partidos = $serie->partidos; @endphp
    <div class="serie-section" id="serie-{{ $serie->id }}">
        <div class="serie-title">
            <h3>
                <i class="fas fa-layer-group"></i>
                {{ $serie->nombre_serie }}
                <span style="color:#475569; font-size:0.75rem; font-weight:400;">&mdash; {{ $serie->disciplina->nombre ?? '' }}</span>
            </h3>
            <a href="{{ route('portal.serie', $serie->id) }}">
                <i class="fas fa-table"></i> Posiciones
            </a>
        </div>

        @if($partidos->count() > 0)
            @foreach($partidos->groupBy('jornada') as $jornada => $jornadaPartidos)
            <div class="jornada-block">
                <div class="jornada-label">Jornada {{ $jornada }}</div>
                <div class="partidos-grid">
                    @foreach($jornadaPartidos as $partido)
                    @php
                        $esDecanso = $partido->es_descanso ?? false;
                        $est = $partido->estado ?? 'pendiente';
                        if ($est === 'finalizado')      { $chipClase = 'chip-fin';  $chipLabel = 'Finalizado'; }
                        elseif ($est === 'en_progreso') { $chipClase = 'chip-prog'; $chipLabel = 'En juego'; }
                        else                            { $chipClase = 'chip-pend'; $chipLabel = 'Pendiente'; }
                    @endphp
                    @if(!$esDecanso)
                    <div class="partido-row {{ $est === 'finalizado' ? 'finalizado' : 'pendiente' }}">
                        <div class="partido-equipos">
                            <span class="equipo-name">
                                {{ $partido->equipoLocal->nombre_equipo ?? $partido->equipoLocal->representante_nombre ?? '—' }}
                            </span>

                            <div class="marcador">
                                @if($est === 'finalizado')
                                    <div class="gol-box">{{ $partido->goles_local ?? 0 }}</div>
                                    <span class="vs-sep">:</span>
                                    <div class="gol-box">{{ $partido->goles_visitante ?? 0 }}</div>
                                @else
                                    <div class="gol-box" style="color:#475569; border-style:dashed;">—</div>
                                    <span class="vs-sep">vs</span>
                                    <div class="gol-box" style="color:#475569; border-style:dashed;">—</div>
                                @endif
                            </div>

                            <span class="equipo-name visitante">
                                {{ $partido->equipoVisitante->nombre_equipo ?? $partido->equipoVisitante->representante_nombre ?? '—' }}
                            </span>
                        </div>

                        <div class="partido-meta">
                            <span class="estado-chip {{ $chipClase }}">{{ $chipLabel }}</span>
                            @if($partido->fecha)
                            <span class="meta-fecha">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}
                            </span>
                            @endif
                            @if($partido->lugar)
                            <span class="meta-lugar">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $partido->lugar->nombre }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
        <div class="empty-fixture">
            <i class="fas fa-calendar-times"></i>
            Esta serie aun no tiene partidos generados.
        </div>
        @endif
    </div>
    @endforeach
@else
<div class="empty-fixture">
    <i class="fas fa-calendar-times"></i>
    Este evento aun no tiene series ni partidos generados.
</div>
@endif

@endsection
