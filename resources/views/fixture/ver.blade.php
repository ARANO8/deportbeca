@extends('layouts.panel')

@section('title', 'Fixture - ' . ($evento->nombre ?? 'Evento'))

@section('styles')
<style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #2563eb;
        --secondary: #8b5cf6;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1e293b;
        --gray: #64748b;
        --gray-light: #f8fafc;
        --border: #e2e8f0;
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --radius: 20px;
        --radius-sm: 12px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .fixture-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: var(--radius);
        padding: 28px 32px;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: var(--shadow-lg);
    }

    .header-info h1 {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .header-info p {
        color: rgba(255,255,255,0.8);
        font-size: 0.8rem;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        padding: 8px 22px;
        border-radius: 40px;
        color: white;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    .bracket-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 28px;
    }

    .serie-bracket {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid var(--border);
    }

    .serie-bracket:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgb(0 0 0 / 0.2);
    }

    .serie-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 18px 20px;
        text-align: center;
    }

    .serie-header h3 {
        color: white;
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
    }

    .serie-badge {
        background: rgba(255,255,255,0.2);
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 0.7rem;
        margin-top: 8px;
        display: inline-block;
        color: white;
    }

    .bracket-tree {
        padding: 20px;
        background: var(--gray-light);
    }

    .jornada-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .jornada-btn {
        background: white;
        border: 1px solid var(--border);
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
    }

    .jornada-btn:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .jornada-btn.active {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-color: transparent;
    }

    .jornada-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .jornada-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .jornada-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--primary);
        margin-bottom: 15px;
        padding-left: 10px;
        border-left: 3px solid var(--primary);
    }

    .match-node {
        background: white;
        border-radius: var(--radius-sm);
        margin-bottom: 14px;
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: all 0.2s;
        border: 1px solid var(--border);
    }

    .match-node:hover {
        transform: translateX(5px);
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(59,130,246,0.15);
    }

    .match-date {
        background: #f1f5f9;
        padding: 8px 14px;
        font-size: 0.7rem;
        color: var(--gray);
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid var(--border);
    }

    .match-teams {
        padding: 14px;
    }

    .team-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
    }

    .team-row:first-child {
        border-bottom: 1px solid #f0f0f0;
    }

    .team-name {
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .team-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
    }

    .team-local-icon {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .team-visit-icon {
        background: #e2e8f0;
        color: var(--gray);
    }

    .score {
        font-weight: 800;
        font-size: 1rem;
        background: #f1f5f9;
        padding: 5px 14px;
        border-radius: 30px;
        min-width: 65px;
        text-align: center;
    }

    .score-local-win {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
    }

    .score-visit-win {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: white;
    }

    .score-draw {
        background: linear-gradient(135deg, var(--warning), #d97706);
        color: white;
    }

    .match-place {
        padding: 10px 14px;
        background: #fafcff;
        font-size: 0.7rem;
        color: var(--gray);
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-result {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        color: white;
        padding: 5px 16px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-result:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(59,130,246,0.4);
    }

    .standings-table {
        background: white;
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-top: 20px;
        border: 1px solid var(--border);
    }

    .standings-table thead {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .standings-table th {
        padding: 10px 6px;
        text-align: center;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .standings-table td {
        padding: 8px 5px;
        text-align: center;
        font-size: 0.7rem;
        border-bottom: 1px solid var(--border);
    }

    .standings-table td:first-child,
    .standings-table th:first-child {
        text-align: left;
        padding-left: 12px;
    }

    .qualified-row {
        background: #ecfdf5;
        border-left: 3px solid var(--success);
    }

    @media (max-width: 768px) {
        .fixture-header {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .header-actions {
            justify-content: center;
        }

        .bracket-container {
            grid-template-columns: 1fr;
        }

        .match-date {
            flex-direction: column;
            gap: 5px;
            text-align: center;
        }

        .team-row {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }

        .team-name {
            justify-content: center;
        }

        .match-place {
            flex-direction: column;
            text-align: center;
        }

        .standings-table th:nth-child(5),
        .standings-table th:nth-child(6),
        .standings-table td:nth-child(5),
        .standings-table td:nth-child(6) {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .serie-header h3 {
            font-size: 1.1rem;
        }

        .jornada-selector {
            justify-content: center;
        }

        .score {
            font-size: 0.9rem;
            padding: 4px 10px;
            min-width: 55px;
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .serie-bracket {
            break-inside: avoid;
            page-break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .fixture-header {
            background: #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .standings-table thead {
            background: #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .btn-result {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="fixture-header">
        <div class="header-info">
            <h1>
                <i class="fas fa-calendar-alt mr-2"></i>
                {{ $evento->nombre ?? 'Fixture Oficial' }}
            </h1>
            <p>
                <i class="fas fa-chart-line mr-1"></i> Sistema Round Robin |
                Generado: {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="header-actions no-print">
            <button onclick="window.print()" class="btn-action">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <a href="{{ route('fixture.index') }}" class="btn-action">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(isset($series) && $series->count() > 0)
        <div class="bracket-container">
            @foreach($series as $serie)
            <div class="serie-bracket">
                <div class="serie-header">
                    <h3>
                        <i class="fas fa-trophy mr-2"></i>{{ $serie->nombre_serie }}
                    </h3>
                    <div class="serie-badge">
                        <i class="fas fa-users mr-1"></i> {{ $serie->cantidad_equipos }} equipos |
                        <i class="fas fa-flag-checkered mr-1"></i> Clasifican: {{ $serie->cuantos_clasifican }}
                    </div>
                </div>

                <div class="bracket-tree">
                    <div class="jornada-selector no-print">
                        @php $jornadas = $serie->partidos->groupBy('jornada')->keys()->sort(); @endphp
                        @foreach($jornadas as $j)
                        <button class="jornada-btn" data-serie="{{ $serie->id }}" data-jornada="{{ $j }}">
                            Jornada {{ $j }}
                        </button>
                        @endforeach
                    </div>

                    @foreach($serie->partidos->groupBy('jornada') as $jornada => $partidos)
                    <div class="jornada-content" id="jornada-{{ $serie->id }}-{{ $jornada }}">
                        <div class="jornada-title">
                            <i class="fas fa-calendar-day mr-1"></i> Jornada {{ $jornada }}
                        </div>
                        @foreach($partidos as $partido)
                        <div class="match-node">
                            <div class="match-date">
                                <span><i class="fas fa-calendar-alt mr-1"></i> {{ $partido->fecha ? date('d/m/Y', strtotime($partido->fecha)) : 'Fecha por definir' }}</span>
                                <span><i class="fas fa-clock mr-1"></i> {{ $partido->hora_inicio ? substr($partido->hora_inicio, 0, 5) : '--:--' }} hrs</span>
                            </div>
                            <div class="match-teams">
                                <div class="team-row">
                                    <div class="team-name">
                                        <span class="team-icon team-local-icon">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        {{ $partido->equipoLocal->nombre_participante ?? 'Local' }}
                                    </div>
                                    <div class="score {{ $partido->goles_local !== null && $partido->goles_local > $partido->goles_visitante ? 'score-local-win' : ($partido->goles_local !== null && $partido->goles_local == $partido->goles_visitante ? 'score-draw' : '') }}">
                                        {{ $partido->goles_local !== null ? $partido->goles_local : 'VS' }}
                                    </div>
                                </div>
                                <div class="team-row">
                                    <div class="team-name">
                                        <span class="team-icon team-visit-icon">
                                            <i class="fas fa-plane"></i>
                                        </span>
                                        {{ $partido->equipoVisitante->nombre_participante ?? 'Visitante' }}
                                    </div>
                                    <div class="score {{ $partido->goles_visitante !== null && $partido->goles_visitante > $partido->goles_local ? 'score-visit-win' : ($partido->goles_visitante !== null && $partido->goles_visitante == $partido->goles_local ? 'score-draw' : '') }}">
                                        {{ $partido->goles_visitante !== null ? $partido->goles_visitante : 'VS' }}
                                    </div>
                                </div>
                            </div>
                            <div class="match-place">
                                <span>
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                    {{ $partido->lugar->nombre ?? 'Lugar por definir' }}
                                </span>
                                <button class="btn-result" data-toggle="modal" data-target="#modalResultado{{ $partido->id }}">
                                    <i class="fas fa-edit mr-1"></i> Resultado
                                </button>
                            </div>
                        </div>

                        <div class="modal fade" id="modalResultado{{ $partido->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 20px;">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 20px 20px 0 0;">
                                        <h5 class="modal-title text-white">
                                            <i class="fas fa-trophy mr-2"></i> Registrar Resultado
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center mb-4">
                                            <div class="bg-light rounded p-3">
                                                <div class="row align-items-center">
                                                    <div class="col-5 text-right">
                                                        <strong>{{ Str::limit($partido->equipoLocal->nombre_participante ?? 'Local', 20) }}</strong>
                                                    </div>
                                                    <div class="col-2">
                                                        <span class="badge badge-dark px-3 py-2 rounded-pill">VS</span>
                                                    </div>
                                                    <div class="col-5 text-left">
                                                        <strong>{{ Str::limit($partido->equipoVisitante->nombre_participante ?? 'Visitante', 20) }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form onsubmit="guardarResultado(event, {{ $partido->id }})">
                                            @csrf
                                            <div class="row">
                                                <div class="col-5">
                                                    <label class="text-center d-block font-weight-bold">Local</label>
                                                    <input type="number" id="goles_local_{{ $partido->id }}" class="form-control form-control-lg text-center" placeholder="0" min="0" required>
                                                </div>
                                                <div class="col-2 text-center pt-4">
                                                    <span class="h3 font-weight-bold">-</span>
                                                </div>
                                                <div class="col-5">
                                                    <label class="text-center d-block font-weight-bold">Visitante</label>
                                                    <input type="number" id="goles_visitante_{{ $partido->id }}" class="form-control form-control-lg text-center" placeholder="0" min="0" required>
                                                </div>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill">
                                                    <i class="fas fa-save mr-2"></i> Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                    @if($serie->estadisticas->count() > 0)
                    <div class="standings-table">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Equipo</th>
                                    <th>PJ</th>
                                    <th>PG</th>
                                    <th>PE</th>
                                    <th>PP</th>
                                    <th>GF</th>
                                    <th>GC</th>
                                    <th>DG</th>
                                    <th>PTS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serie->tabla_posiciones as $idx => $stat)
                                <tr class="@if($idx < $serie->cuantos_clasifican) qualified-row @endif">
                                    <td><strong>{{ $idx + 1 }}</strong></td>
                                    <td style="text-align: left;">
                                        @if($idx == 0)<i class="fas fa-crown text-warning mr-1"></i>
                                        @elseif($idx == 1)<i class="fas fa-medal text-secondary mr-1"></i>
                                        @elseif($idx == 2)<i class="fas fa-medal" style="color:#cd7f32"></i>
                                        @else<i class="fas fa-users text-muted mr-1"></i>@endif
                                        {{ Str::limit($stat->nombre_equipo, 15) }}
                                    </td>
                                    <td>{{ $stat->pj }}</td>
                                    <td class="text-success">{{ $stat->pg }}</td>
                                    <td class="text-warning">{{ $stat->pe }}</td>
                                    <td class="text-danger">{{ $stat->pp }}</td>
                                    <td>{{ $stat->gf }}</td>
                                    <td>{{ $stat->gc }}</td>
                                    <td>{{ $stat->dg }}</td>
                                    <td><strong>{{ $stat->pts }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-3 border">
            <i class="fas fa-info-circle fa-4x text-muted mb-3"></i>
            <h4>No hay fixtures generados</h4>
            <p class="text-muted mb-3">Genera un fixture desde el panel principal</p>
            <a href="{{ route('fixture.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-arrow-left mr-2"></i> Ir a Eventos
            </a>
        </div>
    @endif
</div>

<script>
function guardarResultado(event, partidoId) {
    event.preventDefault();
    let golesLocal = document.getElementById('goles_local_' + partidoId).value;
    let golesVisitante = document.getElementById('goles_visitante_' + partidoId).value;

    fetch('/fixture/partido/' + partidoId + '/resultado', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            goles_local: golesLocal,
            goles_visitante: golesVisitante
        })
    }).then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        }
    }).catch(error => {
        toastr.error('Error al guardar el resultado');
    });
}

$(document).ready(function() {
    $('.jornada-btn').click(function() {
        let serieId = $(this).data('serie');
        let jornada = $(this).data('jornada');

        $(`.jornada-btn[data-serie="${serieId}"]`).removeClass('active');
        $(this).addClass('active');

        $(`.jornada-content[id^="jornada-${serieId}-"]`).removeClass('active');
        $(`#jornada-${serieId}-${jornada}`).addClass('active');
    });

    if($('.jornada-btn').length) {
        $('.jornada-btn:first').click();
    }
});
</script>
@endsection