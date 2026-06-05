@extends('layouts.panel')

@section('title', 'Tabla de Posiciones — ' . $serie->nombre_serie)

@section('styles')
<style>
    :root {
        --red: #dc2626;
        --red-dark: #b91c1c;
        --green: #10b981;
        --yellow: #f59e0b;
        --dark: #1f2937;
        --gray: #6b7280;
        --border: #e5e7eb;
    }

    .hero-cal {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .hero-cal h1 { color: white; font-size: 1.5rem; font-weight: 700; margin: 0 0 4px; }
    .hero-cal p  { color: rgba(255,255,255,0.85); font-size: 0.8rem; margin: 0; }

    .hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }

    .btn-light-custom {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px 22px;
        border-radius: 40px;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s;
    }
    .btn-light-custom:hover { background: rgba(255,255,255,0.28); color: white; text-decoration: none; }

    .card-tabla {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 28px;
    }

    .card-tabla-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--dark);
    }

    .card-tabla-header i { color: var(--red); }

    table.pos-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    table.pos-table thead th {
        background: #f1f5f9;
        color: var(--gray);
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 10px 14px;
        text-align: center;
        border-bottom: 1px solid var(--border);
    }
    table.pos-table thead th:first-child,
    table.pos-table thead th:nth-child(2) { text-align: left; }

    table.pos-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    table.pos-table tbody tr:hover { background: #fafafa; }
    table.pos-table tbody td { padding: 11px 14px; color: var(--dark); text-align: center; }
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
        background: #f1f5f9;
        color: var(--gray);
    }
    .pos-num.clasifica { background: #d1fae5; color: #065f46; }

    .equipo-nombre-tabla { font-weight: 600; color: var(--dark); }
    .equipo-carrera-tabla { font-size: 0.72rem; color: var(--gray); }

    .pts-badge {
        background: var(--red);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.8rem;
    }

    .clasifica-row { background: #f0fdf4 !important; }

    .badge-clasifica {
        font-size: 0.65rem;
        background: #d1fae5;
        color: #065f46;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 700;
        margin-left: 6px;
    }

    .fase-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 2px dashed #fca5a5;
        margin-bottom: 28px;
    }
    .fase-card h5 { color: var(--red); font-weight: 700; margin-bottom: 8px; }

    @media (max-width: 768px) {
        .hero-cal { flex-direction: column; text-align: center; }
        .col-hide-sm { display: none; }
    }

    /* ---- DARK MODE ---- */
    [data-theme="dark"] {
        --dark:   #EAF1F8;
        --gray:   #6B8EAA;
        --border: #1E3450;
    }

    [data-theme="dark"] .card-tabla {
        background: var(--umsa-surface);
        border-color: var(--border);
        box-shadow: 0 4px 16px rgba(0,0,0,0.4);
    }

    [data-theme="dark"] .card-tabla-header {
        background: rgba(255,255,255,0.04);
        border-bottom-color: var(--border);
        color: var(--dark);
    }

    [data-theme="dark"] table.pos-table thead th {
        background: rgba(255,255,255,0.05);
        border-bottom-color: var(--border);
    }

    [data-theme="dark"] table.pos-table tbody tr {
        border-bottom-color: var(--border);
    }

    [data-theme="dark"] table.pos-table tbody tr:hover {
        background: rgba(26,82,118,0.12);
    }

    [data-theme="dark"] table.pos-table tbody td {
        color: var(--dark);
    }

    [data-theme="dark"] .pos-num {
        background: rgba(255,255,255,0.07);
        color: var(--gray);
    }

    [data-theme="dark"] .equipo-nombre-tabla { color: var(--dark); }

    [data-theme="dark"] .clasifica-row {
        background: rgba(16,185,129,0.08) !important;
    }

    [data-theme="dark"] .fase-card {
        background: var(--umsa-surface);
        border-color: rgba(220,38,38,0.3);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">

    {{-- HERO --}}
    <div class="hero-cal">
        <div>
            <h1><i class="fas fa-table mr-2"></i> {{ $serie->nombre_serie }}</h1>
            <p>
                <i class="fas fa-tag mr-1"></i> {{ $serie->disciplina->nombre ?? 'Disciplina' }}
                &nbsp;|&nbsp;
                <i class="fas fa-calendar-alt mr-1"></i> {{ $serie->eventoConfiguracion->nombre ?? 'Evento' }}
                &nbsp;|&nbsp;
                Clasifican: <strong>{{ $serie->cuantos_clasifican }}</strong>
            </p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('exportar.posiciones.excel', $serie->id) }}" class="btn-light-custom" title="Exportar a Excel">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('exportar.posiciones.pdf', $serie->id) }}" class="btn-light-custom" title="Descargar PDF">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('fixture.ver.serie', $serie->id) }}" class="btn-light-custom">
                <i class="fas fa-futbol"></i> Ver Partidos
            </a>
            <a href="{{ route('fixture.mis.fixtures') }}" class="btn-light-custom">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-pill px-4">{{ session('error') }}</div>
    @endif

    {{-- TABLA DE POSICIONES --}}
    <div class="card-tabla">
        <div class="card-tabla-header">
            <i class="fas fa-trophy"></i> Tabla de Posiciones
        </div>
        <div class="table-responsive">
            <table class="pos-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Equipo</th>
                        <th title="Partidos Jugados">PJ</th>
                        <th title="Partidos Ganados">PG</th>
                        <th title="Partidos Empatados">PE</th>
                        <th title="Partidos Perdidos">PP</th>
                        <th title="Goles a Favor">GF</th>
                        <th title="Goles en Contra" class="col-hide-sm">GC</th>
                        <th title="Diferencia de Goles">DG</th>
                        <th title="Tarjetas Amarillas" class="col-hide-sm">TA</th>
                        <th title="Tarjetas Rojas" class="col-hide-sm">TR</th>
                        <th>PTS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tablaPosiciones as $idx => $stat)
                        @php
                            $clasifica = ($idx + 1) <= $serie->cuantos_clasifican;
                            $equipo    = $stat->equipo;
                            $nombre    = $stat->nombre_equipo ?: ($equipo->nombre_participante ?? '—');
                            $carrera   = $equipo?->carrera?->nombre ?? $equipo?->facultad?->nombre ?? '';
                        @endphp
                        <tr class="{{ $clasifica ? 'clasifica-row' : '' }}">
                            <td>
                                <span class="pos-num {{ $clasifica ? 'clasifica' : '' }}">
                                    {{ $idx + 1 }}
                                </span>
                            </td>
                            <td>
                                <span class="equipo-nombre-tabla">{{ $nombre }}</span>
                                @if($clasifica)
                                    <span class="badge-clasifica">Clasifica</span>
                                @endif
                                @if($carrera)
                                    <br><span class="equipo-carrera-tabla">{{ $carrera }}</span>
                                @endif
                            </td>
                            <td>{{ $stat->pj }}</td>
                            <td>{{ $stat->pg }}</td>
                            <td>{{ $stat->pe }}</td>
                            <td>{{ $stat->pp }}</td>
                            <td>{{ $stat->gf }}</td>
                            <td class="col-hide-sm">{{ $stat->gc }}</td>
                            <td>{{ $stat->dg >= 0 ? '+' . $stat->dg : $stat->dg }}</td>
                            <td class="col-hide-sm" style="color: #d97706;">{{ $stat->tarjetas_amarillas }}</td>
                            <td class="col-hide-sm" style="color: #dc2626;">{{ $stat->tarjetas_rojas }}</td>
                            <td><span class="pts-badge">{{ $stat->pts }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Aún no hay resultados registrados para esta serie.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PANEL SIGUIENTE FASE --}}
    @if($todosFinalizados && $serie->tipo_competencia === 'todos_contra_todos')
    <div class="fase-card">
        <h5><i class="fas fa-forward mr-2"></i> Generar Siguiente Fase</h5>
        <p class="text-muted small mb-3">
            Todos los partidos de esta serie están finalizados.
            Al confirmar, el sistema tomará los <strong>{{ $serie->cuantos_clasifican }} primeros</strong>
            de cada grupo de la disciplina <em>{{ $serie->disciplina->nombre ?? '' }}</em>
            y generará automáticamente la siguiente ronda eliminatoria.
        </p>
        <form method="POST"
              action="{{ route('fixture.siguiente.fase', [$serie->evento_configuracion_id, $serie->disciplina_id]) }}">
            @csrf
            <div class="row align-items-end g-2">
                <div class="col-auto">
                    <label class="form-label small fw-bold">Fecha tentativa (opcional)</label>
                    <input type="date" name="fecha_siguiente_fase" class="form-control form-control-sm" style="width:180px;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-danger rounded-pill px-4"
                            onclick="return confirm('¿Confirmar generación de la siguiente fase?')">
                        <i class="fas fa-play mr-1"></i> Generar Siguiente Fase
                    </button>
                </div>
            </div>
        </form>
    </div>
    @elseif(!$todosFinalizados)
    <div class="card-tabla">
        <div class="card-tabla-header" style="color: var(--gray);">
            <i class="fas fa-clock"></i>
            La siguiente fase estará disponible cuando todos los partidos de esta serie estén finalizados.
        </div>
    </div>
    @endif

</div>
@endsection
