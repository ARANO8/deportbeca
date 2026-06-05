@extends('layouts.panel')

@section('title', 'Calificaciones — ' . $serie->nombre_serie)

@section('styles')
<style>
    :root { --red: #dc2626; --red-dark: #b91c1c; --dark: #1f2937; --gray: #6b7280; --border: #e5e7eb; }

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

    .btn-light-custom {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px 22px;
        border-radius: 40px;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s;
    }
    .btn-light-custom:hover { background: rgba(255,255,255,0.28); color: white; text-decoration: none; }

    .card-form {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .card-form-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--dark);
    }

    table.ind-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    table.ind-table thead th {
        background: #f1f5f9;
        color: var(--gray);
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 10px 16px;
        border-bottom: 1px solid var(--border);
    }
    table.ind-table tbody tr { border-bottom: 1px solid var(--border); }
    table.ind-table tbody tr:hover { background: #fafafa; }
    table.ind-table tbody td { padding: 12px 16px; vertical-align: middle; }

    .participante-nombre { font-weight: 600; color: var(--dark); }
    .participante-sub    { font-size: 0.72rem; color: var(--gray); }

    .saved-badge {
        font-size: 0.65rem;
        background: #d1fae5;
        color: #065f46;
        padding: 2px 7px;
        border-radius: 12px;
        font-weight: 700;
        margin-left: 6px;
    }

    /* ---- DARK MODE ---- */
    [data-theme="dark"] {
        --dark:   #EAF1F8;
        --gray:   #6B8EAA;
        --border: #1E3450;
    }

    [data-theme="dark"] .card-form {
        background: var(--umsa-surface);
        border-color: var(--border);
        box-shadow: 0 4px 16px rgba(0,0,0,0.4);
    }

    [data-theme="dark"] .card-form-header {
        background: rgba(255,255,255,0.04);
        border-bottom-color: var(--border);
        color: var(--dark);
    }

    [data-theme="dark"] table.ind-table thead th {
        background: rgba(255,255,255,0.05);
        border-bottom-color: var(--border);
        color: var(--gray);
    }

    [data-theme="dark"] table.ind-table tbody tr {
        border-bottom-color: var(--border);
    }

    [data-theme="dark"] table.ind-table tbody tr:hover {
        background: rgba(26,82,118,0.12);
    }

    [data-theme="dark"] table.ind-table tbody td {
        color: var(--dark);
    }

    [data-theme="dark"] .participante-nombre { color: var(--dark); }

    [data-theme="dark"] .d-flex.border-top {
        border-top-color: var(--border) !important;
        background: rgba(255,255,255,0.03) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">

    <div class="hero-cal">
        <div>
            <h1><i class="fas fa-medal mr-2"></i> {{ $serie->nombre_serie }}</h1>
            <p>
                <i class="fas fa-tag mr-1"></i> {{ $serie->disciplina->nombre ?? 'Disciplina' }}
                &nbsp;|&nbsp;
                <i class="fas fa-user mr-1"></i> Competencia Individual
            </p>
        </div>
        <div>
            <a href="{{ route('fixture.ver.serie', $serie->id) }}" class="btn-light-custom">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4">{{ session('success') }}</div>
    @endif

    <div class="card-form">
        <div class="card-form-header">
            <i class="fas fa-list-ol mr-2" style="color:var(--red)"></i>
            Asignar posiciones finales
        </div>

        <form action="{{ route('calificaciones.guardar.posiciones', $serie->id) }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="ind-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Participante</th>
                            <th>Carrera / Facultad</th>
                            <th style="width:180px">Posición Final</th>
                            <th style="width:160px">Tiempo / Marca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participantes as $idx => $p)
                            @php
                                $stat    = $estadisticas[$p->id] ?? null;
                                $saved   = $stat && $stat->posicion_final;
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <span class="participante-nombre">{{ $p->nombre_participante }}</span>
                                    @if($saved)
                                        <span class="saved-badge">Guardado</span>
                                    @endif
                                    <br>
                                    <span class="participante-sub">Individual</span>
                                </td>
                                <td>
                                    <span class="participante-sub">
                                        {{ $p->carrera->nombre ?? $p->facultad->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <select name="posiciones[{{ $p->id }}]" class="form-select form-select-sm">
                                        <option value="">Seleccionar...</option>
                                        @foreach([1=>'🥇 1er Puesto',2=>'🥈 2do Puesto',3=>'🥉 3er Puesto',
                                                  4=>'4to',5=>'5to',6=>'6to',7=>'7mo',8=>'8vo'] as $v => $label)
                                            <option value="{{ $v }}"
                                                {{ ($stat?->posicion_final == $v) ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text"
                                           name="marca[{{ $p->id }}]"
                                           class="form-control form-control-sm"
                                           placeholder="Ej: 10.5s / 1:30:00"
                                           value="{{ $stat?->marca ?? '' }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 p-3 border-top bg-light justify-content-end">
                <a href="{{ route('fixture.ver.serie', $serie->id) }}" class="btn btn-secondary btn-sm rounded-pill px-4">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">
                    <i class="fas fa-save mr-1"></i> Guardar Clasificación
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
