@extends('layouts.portal')

@section('title', $serie->nombre_serie . ' — Posiciones')

@section('styles')
<style>
    .serie-hero {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .serie-hero h1 { color: white; font-size: 1.4rem; font-weight: 800; margin: 0 0 4px; }
    .serie-hero p  { color: rgba(255,255,255,0.85); font-size: 0.8rem; margin: 0; }
    .hero-links { display: flex; gap: 8px; flex-wrap: wrap; }
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

    .tabla-wrap {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .tabla-header {
        padding: 14px 18px;
        background: rgba(220,38,38,0.1);
        border-bottom: 1px solid #334155;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        color: #f1f5f9;
    }
    .tabla-header i { color: #dc2626; }

    table.pos-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
    table.pos-table thead th {
        background: rgba(255,255,255,0.04);
        color: #64748b;
        font-weight: 700;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 10px 14px;
        text-align: center;
        border-bottom: 1px solid #334155;
    }
    table.pos-table thead th:first-child,
    table.pos-table thead th:nth-child(2) { text-align: left; }

    table.pos-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.15s; }
    table.pos-table tbody tr:hover { background: rgba(255,255,255,0.03); }
    table.pos-table tbody td { padding: 11px 14px; color: #e2e8f0; text-align: center; }
    table.pos-table tbody td:nth-child(2) { text-align: left; }

    .pos-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.8rem;
        background: #334155;
        color: #94a3b8;
    }
    .pos-gold   { background: #78350f; color: #fcd34d; }
    .pos-silver { background: #374151; color: #d1d5db; }
    .pos-bronze { background: #431407; color: #fb923c; }
    .pos-clasifica { background: rgba(16,185,129,0.15); color: #10b981; }

    .equipo-nombre { font-weight: 600; color: #f1f5f9; }
    .equipo-carrera { font-size: 0.7rem; color: #64748b; }

    .pts-badge {
        background: #dc2626;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.78rem;
    }

    .clasifica-row { background: rgba(16,185,129,0.04) !important; }
    .badge-clasifica {
        font-size: 0.62rem;
        background: rgba(16,185,129,0.15);
        color: #10b981;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 700;
        margin-left: 5px;
    }

    .marca-chip {
        background: rgba(59,130,246,0.12);
        color: #60a5fa;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 0.74rem;
    }

    .pos-ind {
        font-weight: 800;
        font-size: 0.9rem;
    }
    .pos-1 { color: #fcd34d; }
    .pos-2 { color: #d1d5db; }
    .pos-3 { color: #fb923c; }

    .legend-box {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.74rem;
        color: #64748b;
    }
    .legend-item strong { color: #94a3b8; }

    .empty-standings {
        text-align: center;
        padding: 40px;
        color: #475569;
        font-size: 0.85rem;
    }
    .empty-standings i { font-size: 2rem; margin-bottom: 10px; display: block; opacity: 0.4; }

    @media (max-width: 768px) {
        .col-hide { display: none; }
    }
</style>
@endsection

@section('content')

<!-- Hero -->
<div class="serie-hero">
    <div>
        <h1><i class="fas fa-table mr-2"></i>{{ $serie->nombre_serie }}</h1>
        <p>
            <i class="fas fa-tag mr-1"></i> {{ $serie->disciplina->nombre ?? 'Disciplina' }}
            &nbsp;|&nbsp;
            <i class="fas fa-calendar-alt mr-1"></i> {{ $serie->eventoConfiguracion->nombre ?? 'Evento' }}
            @if(!$esIndividual)
                &nbsp;|&nbsp; Clasifican: <strong>{{ $serie->cuantos_clasifican }}</strong>
            @endif
        </p>
    </div>
    <div class="hero-links">
        <a href="{{ route('portal.fixture', $serie->evento_configuracion_id) }}" class="btn-h">
            <i class="fas fa-calendar-week"></i> Ver Fixture
        </a>
        <a href="{{ route('portal.evento', $serie->evento_configuracion_id) }}" class="btn-h">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- Tabla de posiciones -->
<div class="tabla-wrap">
    <div class="tabla-header">
        <i class="fas fa-trophy"></i>
        @if($esIndividual)
            Tabla de Posiciones Individuales
        @else
            Tabla de Posiciones Grupales
        @endif
    </div>
    <div class="table-responsive">
        @if($tablaPosiciones->count() > 0)
        <table class="pos-table">
            <thead>
                @if($esIndividual)
                <tr>
                    <th>#</th>
                    <th>Participante</th>
                    <th>Posicion</th>
                    <th>Marca / Tiempo</th>
                </tr>
                @else
                <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th title="Partidos Jugados">PJ</th>
                    <th title="Partidos Ganados">PG</th>
                    <th title="Partidos Empatados">PE</th>
                    <th title="Partidos Perdidos">PP</th>
                    <th title="Goles a Favor">GF</th>
                    <th class="col-hide" title="Goles en Contra">GC</th>
                    <th title="Diferencia de Goles">DG</th>
                    <th class="col-hide" title="Tarjetas Amarillas">TA</th>
                    <th class="col-hide" title="Tarjetas Rojas">TR</th>
                    <th>PTS</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @foreach($tablaPosiciones as $idx => $stat)
                @php
                    $equipo  = $stat->equipo;
                    $nombre  = $stat->nombre_equipo ?: ($equipo->representante_nombre ?? $equipo->nombre_equipo ?? '—');
                    $carrera = $equipo?->carrera?->nombre ?? $equipo?->facultad?->nombre ?? '';
                    $clasifica = !$esIndividual && ($idx + 1) <= $serie->cuantos_clasifican;

                    if ($esIndividual) {
                        $pos = $stat->posicion_final;
                        if ($pos == 1)      $posClase = 'pos-1';
                        elseif ($pos == 2)  $posClase = 'pos-2';
                        elseif ($pos == 3)  $posClase = 'pos-3';
                        else                $posClase = '';
                    }
                @endphp
                <tr class="{{ $clasifica ? 'clasifica-row' : '' }}">
                    <td>
                        @if($esIndividual)
                            <span class="pos-ind {{ $posClase ?? '' }}">{{ $idx + 1 }}</span>
                        @else
                            @php
                                $numPos = $idx + 1;
                                if ($numPos == 1)      $posNumClase = 'pos-gold';
                                elseif ($numPos == 2)  $posNumClase = 'pos-silver';
                                elseif ($numPos == 3)  $posNumClase = 'pos-bronze';
                                elseif ($clasifica)    $posNumClase = 'pos-clasifica';
                                else                   $posNumClase = '';
                            @endphp
                            <span class="pos-num {{ $posNumClase }}">{{ $numPos }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="equipo-nombre">{{ $nombre }}</span>
                        @if($clasifica)
                            <span class="badge-clasifica">Clasifica</span>
                        @endif
                        @if($carrera)
                            <br><span class="equipo-carrera">{{ $carrera }}</span>
                        @endif
                    </td>
                    @if($esIndividual)
                    <td>
                        @if($stat->posicion_final)
                            <span class="{{ $posClase ?? '' }}" style="font-weight:700;">
                                {{ $stat->posicion_final == 1 ? '1er' : ($stat->posicion_final == 2 ? '2do' : ($stat->posicion_final == 3 ? '3er' : $stat->posicion_final . 'to')) }} Puesto
                            </span>
                        @else
                            <span style="color:#475569;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($stat->marca)
                            <span class="marca-chip">{{ $stat->marca }}</span>
                        @else
                            <span style="color:#475569;">—</span>
                        @endif
                    </td>
                    @else
                    <td>{{ $stat->pj }}</td>
                    <td>{{ $stat->pg }}</td>
                    <td>{{ $stat->pe }}</td>
                    <td>{{ $stat->pp }}</td>
                    <td>{{ $stat->gf }}</td>
                    <td class="col-hide">{{ $stat->gc }}</td>
                    <td>{{ $stat->dg >= 0 ? '+' . $stat->dg : $stat->dg }}</td>
                    <td class="col-hide" style="color: #f59e0b;">{{ $stat->tarjetas_amarillas }}</td>
                    <td class="col-hide" style="color: #f87171;">{{ $stat->tarjetas_rojas }}</td>
                    <td><span class="pts-badge">{{ $stat->pts }}</span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-standings">
            <i class="fas fa-info-circle"></i>
            Aun no hay resultados registrados para esta serie.
        </div>
        @endif
    </div>
</div>

@if(!$esIndividual && $tablaPosiciones->count() > 0)
<!-- Leyenda -->
<div class="legend-box">
    <div class="legend-item"><strong>PJ</strong> Partidos Jugados</div>
    <div class="legend-item"><strong>PG</strong> Ganados</div>
    <div class="legend-item"><strong>PE</strong> Empatados</div>
    <div class="legend-item"><strong>PP</strong> Perdidos</div>
    <div class="legend-item"><strong>GF</strong> Goles a Favor</div>
    <div class="legend-item"><strong>GC</strong> Goles en Contra</div>
    <div class="legend-item"><strong>DG</strong> Diferencia de Goles</div>
    <div class="legend-item"><strong>TA</strong> Tarjetas Amarillas</div>
    <div class="legend-item"><strong>TR</strong> Tarjetas Rojas</div>
    <div class="legend-item"><strong>PTS</strong> Puntos</div>
</div>
@endif

@endsection
