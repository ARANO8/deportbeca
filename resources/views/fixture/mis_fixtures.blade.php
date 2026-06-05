@extends('layouts.panel')

@section('title', 'Mis Fixtures')

@section('styles')
<style>
    :root {
        --red: #dc2626;
        --red-dark: #b91c1c;
        --red-light: #fee2e2;
        --dark: #1f2937;
        --gray: #6b7280;
        --gray-light: #f9fafb;
        --border: #e5e7eb;
        --white: #ffffff;
    }

    /* Header */
    .page-header {
        margin-bottom: 32px;
    }

    .page-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220,38,38,0.3);
        color: white;
    }

    /* Tarjeta de fixture */
    .fixture-card {
        background: var(--white);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        margin-bottom: 28px;
        border: 1px solid var(--border);
    }

    .fixture-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    /* Header de la tarjeta */
    .fixture-header {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        padding: 20px 28px;
        position: relative;
        overflow: hidden;
    }

    .fixture-header::before {
        content: '🏆';
        position: absolute;
        bottom: -15px;
        right: 20px;
        font-size: 80px;
        opacity: 0.08;
    }

    .fixture-header h3 {
        color: white;
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 6px 0;
    }

    .fixture-header p {
        color: rgba(255,255,255,0.85);
        font-size: 0.75rem;
        margin: 0;
    }

    .badge-count {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        padding: 6px 16px;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
    }

    /* Cuerpo de la tarjeta */
    .fixture-body {
        padding: 24px 28px;
    }

    .section-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-label i {
        color: var(--red);
        font-size: 0.8rem;
    }

    /* Badges de series */
    .series-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    .serie-badge {
        background: var(--gray-light);
        border: 1px solid var(--border);
        padding: 10px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--dark);
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .serie-badge i {
        color: var(--red);
        font-size: 0.9rem;
    }

    .serie-badge small {
        font-weight: 400;
        color: var(--gray);
        font-size: 0.7rem;
    }

    .serie-badge .hora-badge {
        background: #e5e7eb;
        padding: 2px 8px;
        border-radius: 30px;
        font-size: 0.65rem;
        color: var(--gray);
    }

    .serie-badge:hover {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.2);
    }

    .serie-badge:hover i,
    .serie-badge:hover small,
    .serie-badge:hover .hora-badge,
    .serie-badge:hover span {
        color: white !important;
    }

    .serie-badge:hover .hora-badge {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Botones de acción */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    .btn-calendar {
        background: transparent;
        border: 1px solid var(--border);
        padding: 8px 20px;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--gray);
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-calendar:hover {
        background: var(--gray-light);
        border-color: var(--red);
        color: var(--red);
    }

    .btn-print {
        background: var(--red);
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 500;
        color: white;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-print:hover {
        background: var(--red-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.3);
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray);
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-size: 1.2rem;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 20px;
    }

    /* ---- DARK MODE ---- */
    [data-theme="dark"] {
        --dark:       #EAF1F8;
        --white:      #152236;
        --gray-light: #1A2F47;
        --border:     #1E3450;
        --gray:       #6B8EAA;
    }

    [data-theme="dark"] .fixture-card {
        background: var(--white);
        border-color: var(--border);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.45);
    }

    [data-theme="dark"] .fixture-body {
        background: var(--white);
    }

    [data-theme="dark"] .section-label {
        color: var(--gray);
    }

    [data-theme="dark"] .serie-badge {
        background: var(--gray-light);
        border-color: var(--border);
        color: var(--dark);
    }

    [data-theme="dark"] .serie-badge small {
        color: var(--gray);
    }

    [data-theme="dark"] .serie-badge .hora-badge {
        background: rgba(255, 255, 255, 0.07);
        color: var(--gray);
    }

    [data-theme="dark"] .action-buttons {
        border-color: var(--border);
    }

    [data-theme="dark"] .btn-calendar {
        border-color: var(--border);
        color: var(--gray);
    }

    [data-theme="dark"] .btn-calendar:hover {
        background: var(--gray-light);
    }

    [data-theme="dark"] .empty-state {
        background: var(--white);
        border-color: var(--border);
    }

    [data-theme="dark"] .empty-state h4,
    [data-theme="dark"] .page-header h2 {
        color: var(--dark) !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .fixture-header {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }

        .fixture-body {
            padding: 20px;
        }

        .series-container {
            justify-content: center;
        }

        .action-buttons {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h2>
            <i class="fas fa-calendar-alt mr-2" style="color: var(--red);"></i>
            Mis Fixtures
        </h2>
        <a href="{{ route('fixture.index') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus mr-2"></i> Generar Nuevo Fixture
        </a>
    </div>

    @php
        $seriesPorEvento = \App\Models\Serie::with(['disciplina', 'eventoConfiguracion'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('evento_configuracion_id');
    @endphp

    @if($seriesPorEvento->count() > 0)
        @foreach($seriesPorEvento as $eventoId => $seriesDelEvento)
            @php $evento = $seriesDelEvento->first()->eventoConfiguracion; @endphp
            @if($evento)
                @foreach($seriesDelEvento->groupBy(function($serie) {
                    return $serie->created_at->format('Y-m-d H:i');
                }) as $fechaCreacion => $seriesGrupo)
                <div class="fixture-card">
                    <div class="fixture-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3>
                                <i class="fas fa-trophy mr-2"></i> {{ $evento->nombre }}
                            </h3>
                            <p>
                                <i class="fas fa-tag mr-1"></i> {{ ucfirst($evento->tipo_evento) }} |
                                <i class="fas fa-clock mr-1"></i> Generado: {{ \Carbon\Carbon::parse($fechaCreacion)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <span class="badge-count">
                                <i class="fas fa-layer-group mr-1"></i> {{ $seriesGrupo->count() }} series
                            </span>
                        </div>
                    </div>

                    <div class="fixture-body">
                        <div class="section-label">
                            <i class="fas fa-trophy"></i>
                            <span>Series de este fixture</span>
                        </div>

                        <div class="series-container">
                            @foreach($seriesGrupo as $serie)
                            <a href="{{ route('fixture.ver.serie', $serie->id) }}" class="serie-badge">
                                <i class="fas fa-futbol"></i>
                                <span>{{ $serie->nombre_serie }}</span>
                                <small>({{ $serie->disciplina->nombre ?? 'Sin disciplina' }})</small>
                                <span class="hora-badge">{{ $serie->created_at->format('H:i') }}</span>
                            </a>
                            @endforeach
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('fixture.calendario', $evento->id) }}?fecha={{ urlencode($fechaCreacion) }}" class="btn-calendar">
                                <i class="fas fa-calendar-week"></i> Ver Calendario
                            </a>
                            <button onclick="imprimirFixture({{ $evento->id }}, '{{ $fechaCreacion }}')" class="btn-print">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                            <a href="{{ route('exportar.fixture.pdf', $evento->id) }}"
                               class="btn-print" style="background:#dc2626; color:#fff; text-decoration:none; padding: 8px 18px; border-radius: 8px; font-size:.8rem; font-weight:600; display:inline-flex; align-items:center; gap:6px;"
                               title="Descargar PDF del fixture">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h4>No hay fixtures generados</h4>
            <p>Comienza generando un nuevo fixture</p>
            <a href="{{ route('fixture.index') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus mr-2"></i> Generar Fixture
            </a>
        </div>
    @endif
</div>

<script>
function imprimirFixture(eventoId, fecha) {
    window.open('/fixture/evento/' + eventoId + '/imprimir?fecha=' + encodeURIComponent(fecha), '_blank');
}
</script>
@endsection