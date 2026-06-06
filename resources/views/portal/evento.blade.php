@extends('layouts.portal')

@section('title', $evento->nombre . ' — Resultados')

@section('styles')
<style>
    .evento-hero {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .evento-hero h1 { color: white; font-size: 1.6rem; font-weight: 800; margin: 0 0 6px; }
    .evento-hero p  { color: rgba(255,255,255,0.85); font-size: 0.82rem; margin: 0; }
    .hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-hero {
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-hero-white {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
    }
    .btn-hero-white:hover { background: rgba(255,255,255,0.28); color: white; }

    .disciplina-section { margin-bottom: 36px; }
    .disciplina-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        font-weight: 700;
        color: #f1f5f9;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 2px solid #334155;
    }
    .disciplina-title i { color: #dc2626; }

    .series-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 14px;
    }

    .serie-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 14px;
        padding: 18px 20px;
        transition: all 0.2s;
    }
    .serie-card:hover {
        border-color: #dc2626;
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.3);
    }
    .serie-card h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #f1f5f9;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .serie-card h4 i { color: #dc2626; }

    .serie-stats {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .stat-chip {
        background: rgba(255,255,255,0.05);
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.72rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .stat-chip i { color: #dc2626; font-size: 0.65rem; }

    .serie-estado {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 14px;
        display: inline-block;
    }
    .estado-pending  { background: #334155; color: #94a3b8; }
    .estado-progress { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
    .estado-finished { background: rgba(220,38,38,0.12); color: #f87171; border: 1px solid rgba(220,38,38,0.25); }

    .serie-actions {
        display: flex;
        gap: 7px;
    }
    .btn-sm-portal {
        flex: 1;
        text-align: center;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 0.74rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    .btn-sm-red  { background: #dc2626; color: white; }
    .btn-sm-red:hover { background: #b91c1c; color: white; }
    .btn-sm-ghost { background: transparent; border: 1px solid #334155; color: #94a3b8; }
    .btn-sm-ghost:hover { border-color: #dc2626; color: #f87171; }

    .empty-disc {
        background: rgba(255,255,255,0.03);
        border: 1px dashed #334155;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        color: #475569;
        font-size: 0.82rem;
    }
</style>
@endsection

@section('content')

<!-- Hero -->
<div class="evento-hero">
    <div>
        <h1><i class="fas fa-trophy mr-2"></i>{{ $evento->nombre }}</h1>
        <p>
            <i class="fas fa-tag mr-1"></i> {{ ucfirst($evento->tipo_evento) }}
            @if($evento->fecha_inicio)
                &nbsp;|&nbsp; <i class="fas fa-calendar-alt mr-1"></i>
                {{ $evento->fecha_inicio->format('d/m/Y') }}
                @if($evento->fecha_fin)
                    &mdash; {{ $evento->fecha_fin->format('d/m/Y') }}
                @endif
            @endif
        </p>
    </div>
    <div class="hero-actions">
        <a href="{{ route('portal.fixture', $evento->id) }}" class="btn-hero btn-hero-white">
            <i class="fas fa-calendar-week"></i> Ver Fixture
        </a>
        <a href="{{ route('portal.index') }}" class="btn-hero btn-hero-white">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

@if($seriesPorDisciplina->count() > 0)
    @foreach($seriesPorDisciplina as $disciplinaId => $series)
        @php $disciplina = $series->first()->disciplina; @endphp
        <div class="disciplina-section">
            <div class="disciplina-title">
                <i class="fas fa-futbol"></i>
                {{ $disciplina->nombre ?? 'Sin disciplina' }}
                <span style="color: #475569; font-size: 0.75rem; font-weight: 400;">({{ $series->count() }} {{ $series->count() === 1 ? 'serie' : 'series' }})</span>
            </div>

            <div class="series-grid">
                @foreach($series as $serie)
                @php
                    $totalPartidos  = $serie->partidos->count();
                    $partidosFin    = $serie->partidos->where('estado', 'finalizado')->count();
                    $totalEquipos   = $serie->preinscripciones->count();
                    $pct = $totalPartidos > 0 ? round(($partidosFin / $totalPartidos) * 100) : 0;
                    if ($totalPartidos === 0) {
                        $estadoClase = 'estado-pending';
                        $estadoLabel = 'Sin partidos';
                    } elseif ($partidosFin === $totalPartidos) {
                        $estadoClase = 'estado-finished';
                        $estadoLabel = 'Finalizado';
                    } else {
                        $estadoClase = 'estado-progress';
                        $estadoLabel = "En curso ({$partidosFin}/{$totalPartidos})";
                    }
                @endphp
                <div class="serie-card">
                    <h4>
                        <i class="fas fa-layer-group"></i>
                        {{ $serie->nombre_serie }}
                    </h4>

                    <div class="serie-stats">
                        <span class="stat-chip"><i class="fas fa-users"></i> {{ $totalEquipos }} equipos</span>
                        <span class="stat-chip"><i class="fas fa-futbol"></i> {{ $totalPartidos }} partidos</span>
                        <span class="stat-chip"><i class="fas fa-check"></i> {{ $partidosFin }} terminados</span>
                    </div>

                    <span class="serie-estado {{ $estadoClase }}">{{ $estadoLabel }}</span>

                    <div class="serie-actions">
                        <a href="{{ route('portal.serie', $serie->id) }}" class="btn-sm-portal btn-sm-red">
                            <i class="fas fa-table"></i> Posiciones
                        </a>
                        <a href="{{ route('portal.fixture', $evento->id) }}#serie-{{ $serie->id }}" class="btn-sm-portal btn-sm-ghost">
                            <i class="fas fa-list"></i> Partidos
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endforeach
@else
<div class="empty-disc">
    <i class="fas fa-info-circle mr-2"></i>
    Este evento aun no tiene series generadas.
</div>
@endif

@endsection
