@extends('layouts.panel')

@section('title', $serie->nombre_serie)

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --red: #dc2626;
        --red-dark: #b91c1c;
        --red-light: #fee2e2;
        --green: #10b981;
        --yellow: #f59e0b;
        --dark: #1f2937;
        --gray: #6b7280;
        --gray-light: #f9fafb;
        --border: #e5e7eb;
        --white: #ffffff;
    }

    .main-content {
        position: relative;
        z-index: 1;
    }

    .hero-serie {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 10;
    }

    .hero-info h1 {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .hero-info p {
        color: rgba(255,255,255,0.85);
        font-size: 0.8rem;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
        position: relative;
        z-index: 20;
    }

    .btn-light-custom {
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

    .btn-light-custom:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        border: 1px solid var(--border);
    }

    .stat-card i {
        font-size: 2rem;
        margin-bottom: 8px;
        color: var(--red);
    }

    .stat-card h3 {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
        color: var(--dark);
    }

    .stat-card small {
        color: var(--gray);
        font-size: 0.75rem;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .jornada-selector {
        background: var(--gray-light);
        padding: 16px 20px 0 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        border-bottom: 1px solid var(--border);
    }

    .jornada-btn {
        background: white;
        border: 1px solid var(--border);
        padding: 8px 24px;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
    }

    .jornada-btn:hover {
        background: #f3f4f6;
        transform: translateY(-2px);
    }

    .jornada-btn.active {
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        color: white;
        border-color: transparent;
    }

    .partidos-container {
        padding: 20px;
    }

    .jornada-content {
        display: none;
        animation: fadeSlideUp 0.3s ease;
    }

    .jornada-content.active {
        display: block;
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .jornada-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--red);
        margin-bottom: 18px;
        padding-left: 12px;
        border-left: 3px solid var(--red);
    }

    .partido-row {
        background: white;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.2s;
    }

    .partido-row:hover {
        border-color: var(--red);
        box-shadow: 0 2px 8px rgba(220,38,38,0.1);
    }

    .partido-header {
        background: #f8fafc;
        padding: 8px 16px;
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        color: var(--gray);
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 10px;
    }

    .partido-header i {
        margin-right: 4px;
    }

    .partido-body-inline {
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .equipos-inline {
        flex: 2;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .equipo-nombre {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--dark);
    }

    .equipo-local {
        color: var(--red);
    }

    .equipo-visitante {
        color: var(--dark);
    }

    .vs-circle {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.7rem;
    }

    .goles-inline {
        min-width: 80px;
        text-align: center;
    }

    .goles-badge {
        font-weight: 800;
        font-size: 1rem;
        padding: 5px 14px;
        border-radius: 30px;
        display: inline-block;
        background: #f1f5f9;
        color: var(--gray);
    }

    .goles-badge.local-win {
        background: linear-gradient(135deg, var(--green), #059669);
        color: white;
    }

    .goles-badge.visit-win {
        background: linear-gradient(135deg, var(--red), #b91c1c);
        color: white;
    }

    .goles-badge.draw {
        background: linear-gradient(135deg, var(--yellow), #d97706);
        color: white;
    }

    .accion-inline {
        min-width: 100px;
        text-align: right;
    }

    .btn-resultado {
        background: var(--red);
        border: none;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-resultado:hover {
        background: var(--red-dark);
        transform: scale(1.02);
    }

    @media (max-width: 768px) {
        .hero-serie {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .partido-body-inline {
            flex-direction: column;
            text-align: center;
        }

        .equipos-inline {
            justify-content: center;
        }

        .accion-inline {
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .hero-info h1 {
            font-size: 1.3rem;
        }

        .stat-card h3 {
            font-size: 1.5rem;
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .partido-row {
            break-inside: avoid;
        }
        .hero-serie {
            background: #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4" style="position: relative; z-index: 5;">
    <div class="hero-serie">
        <div class="hero-info">
            <h1>
                <i class="fas fa-trophy mr-2"></i> {{ $serie->nombre_serie }}
            </h1>
            <p>
                <i class="fas fa-tag mr-1"></i> {{ $serie->disciplina->nombre ?? 'Disciplina' }} | 
                <i class="fas fa-calendar-alt mr-1"></i> {{ $serie->eventoConfiguracion->nombre ?? 'Evento' }}
            </p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('calificaciones.index', $serie->id) }}" class="btn-light-custom">
                <i class="fas fa-trophy mr-1"></i>
                {{ $esIndividual ? 'Calificaciones' : 'Tabla de Posiciones' }}
            </a>
            <button onclick="window.print()" class="btn-light-custom">
                <i class="fas fa-print mr-2"></i> Imprimir
            </button>
            <a href="{{ url('/fixture/mis-fixtures') }}" class="btn-light-custom">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3>{{ $serie->cantidad_equipos }}</h3>
            <small>Equipos participantes</small>
        </div>
        <div class="stat-card">
            <i class="fas fa-futbol"></i>
            <h3>{{ $serie->partidos->count() }}</h3>
            <small>Partidos totales</small>
        </div>
        <div class="stat-card">
            <i class="fas fa-trophy"></i>
            <h3>{{ $serie->cuantos_clasifican }}</h3>
            <small>Clasifican a siguiente ronda</small>
        </div>
    </div>

    {{-- VISTA PARA DISCIPLINAS INDIVIDUALES (sin partidos) --}}
    @if($esIndividual)
    <div class="main-card" style="padding: 30px; text-align:center;">
        <i class="fas fa-medal" style="font-size:3rem; color:#dc2626; margin-bottom:16px; display:block;"></i>
        <h4 style="font-weight:700; color:#1f2937;">Disciplina Individual</h4>
        <p class="text-muted mb-4">
            Esta disciplina no genera partidos. Las posiciones se asignan directamente
            basadas en tiempo o marca de cada atleta.
        </p>
        <a href="{{ route('calificaciones.index', $serie->id) }}" class="btn btn-danger rounded-pill px-5">
            <i class="fas fa-medal mr-2"></i> Ir a Calificaciones / Posiciones
        </a>
    </div>
    @else

    {{-- TABLA DE POSICIONES RESUMIDA (grupal) --}}
    @if($tablaPosiciones->isNotEmpty())
    <div class="main-card no-print" style="margin-bottom: 20px; overflow:hidden;">
        <div style="padding:14px 20px; background:#f8fafc; border-bottom:1px solid #e5e7eb; font-weight:700; font-size:0.85rem; color:#1f2937;">
            <i class="fas fa-table mr-2" style="color:#dc2626;"></i> Posiciones Actuales
        </div>
        <div style="padding:12px 16px;">
            <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                <thead>
                    <tr style="color:#6b7280; font-size:0.7rem; text-transform:uppercase;">
                        <th style="padding:6px 8px; text-align:left;">#</th>
                        <th style="padding:6px 8px; text-align:left;">Equipo</th>
                        <th style="padding:6px 8px; text-align:center;">PJ</th>
                        <th style="padding:6px 8px; text-align:center;">PG</th>
                        <th style="padding:6px 8px; text-align:center;">PP</th>
                        <th style="padding:6px 8px; text-align:center;">DG</th>
                        <th style="padding:6px 8px; text-align:center;">PTS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tablaPosiciones as $idx => $stat)
                    @php
                        $clasifica = ($idx + 1) <= $serie->cuantos_clasifican;
                        $nombre    = $stat->nombre_equipo ?: ($stat->equipo?->nombre_participante ?? '—');
                    @endphp
                    <tr style="border-top:1px solid #f1f5f9; {{ $clasifica ? 'background:#f0fdf4;' : '' }}">
                        <td style="padding:7px 8px; font-weight:800; color:{{ $clasifica ? '#065f46' : '#6b7280' }};">
                            {{ $idx + 1 }}
                        </td>
                        <td style="padding:7px 8px; font-weight:600;">
                            {{ $nombre }}
                            @if($clasifica) <span style="font-size:0.6rem;background:#d1fae5;color:#065f46;padding:1px 6px;border-radius:10px;margin-left:4px;">Clasifica</span> @endif
                        </td>
                        <td style="padding:7px 8px; text-align:center;">{{ $stat->pj }}</td>
                        <td style="padding:7px 8px; text-align:center;">{{ $stat->pg }}</td>
                        <td style="padding:7px 8px; text-align:center;">{{ $stat->pp }}</td>
                        <td style="padding:7px 8px; text-align:center;">{{ $stat->dg >= 0 ? '+' . $stat->dg : $stat->dg }}</td>
                        <td style="padding:7px 8px; text-align:center;">
                            <span style="background:#dc2626;color:white;padding:2px 9px;border-radius:20px;font-weight:800;">{{ $stat->pts }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="main-card">
        <div class="jornada-selector no-print">
            @php $jornadas = $serie->partidos->groupBy('jornada')->keys()->sort(); @endphp
            @foreach($jornadas as $j)
            <button class="jornada-btn" data-jornada="{{ $j }}">
                Jornada {{ $j }}
            </button>
            @endforeach
        </div>

        <div class="partidos-container">
            @foreach($serie->partidos->groupBy('jornada') as $jornada => $partidos)
            <div class="jornada-content" data-jornada="{{ $jornada }}">
                <div class="jornada-title">
                    <i class="fas fa-calendar-day mr-2"></i> Jornada {{ $jornada }}
                </div>
                @foreach($partidos as $partido)
                <div class="partido-row">
                    <div class="partido-header">
                        <span><i class="fas fa-calendar-alt"></i> {{ $partido->fecha ? date('d/m/Y', strtotime($partido->fecha)) : 'Fecha por definir' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $partido->hora_inicio ? substr($partido->hora_inicio, 0, 5) : '--:--' }} hrs</span>
                        <span><i class="fas fa-map-marker-alt"></i> {{ $partido->lugar->nombre ?? 'Lugar por definir' }}</span>
                    </div>
                    <div class="partido-body-inline">
                        <div class="equipos-inline">
                            <span class="equipo-nombre equipo-local">{{ $partido->equipoLocal->nombre_participante ?? 'Local' }}</span>
                            <div class="vs-circle">VS</div>
                            <span class="equipo-nombre equipo-visitante">{{ $partido->equipoVisitante->nombre_participante ?? 'Visitante' }}</span>
                        </div>
                        <div class="goles-inline">
                            @php
                                $gL = $partido->goles_local;
                                $gV = $partido->goles_visitante;
                                $clase = '';
                                if ($gL !== null && $gV !== null) {
                                    if ($gL > $gV) $clase = 'local-win';
                                    elseif ($gV > $gL) $clase = 'visit-win';
                                    else $clase = 'draw';
                                }
                            @endphp
                            <span class="goles-badge {{ $clase }}">
                                {{ $gL !== null ? $gL : '-' }} - {{ $gV !== null ? $gV : '-' }}
                            </span>
                        </div>
                        <div class="accion-inline">
                            <button class="btn-resultado" data-toggle="modal" data-target="#modalResultadoDetallado{{ $partido->id }}">
                                <i class="fas fa-edit mr-1"></i> RESULTADO
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal para resultado detallado grupal -->
                <div class="modal fade" id="modalResultadoDetallado{{ $partido->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 20px;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title text-white">
                                    <i class="fas fa-trophy mr-2"></i> Registrar Resultado Detallado
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-3">
                                    <div class="bg-light rounded p-2">
                                        <div class="row align-items-center">
                                            <div class="col-5 text-right">
                                                <strong>{{ Str::limit($partido->equipoLocal->nombre_participante ?? 'Local', 15) }}</strong>
                                            </div>
                                            <div class="col-2">
                                                <span class="badge bg-secondary px-2 py-1 rounded-pill">VS</span>
                                            </div>
                                            <div class="col-5 text-left">
                                                <strong>{{ Str::limit($partido->equipoVisitante->nombre_participante ?? 'Visitante', 15) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <form onsubmit="guardarResultadoDetallado(event, {{ $partido->id }})">
                                    @csrf
                                    <div class="row">
                                        <div class="col-5">
                                            <label class="font-weight-bold">{{ Str::limit($partido->equipoLocal->nombre_participante ?? 'Local', 15) }}</label>
                                            <input type="number" id="goles_local_{{ $partido->id }}" class="form-control text-center mb-2" placeholder="Goles" min="0" required>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number" id="tarjetas_amarillas_local_{{ $partido->id }}" class="form-control text-center" placeholder="🟨 Amarillas" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" id="tarjetas_rojas_local_{{ $partido->id }}" class="form-control text-center" placeholder="🟥 Rojas" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-center pt-4">
                                            <span class="h3 font-weight-bold">-</span>
                                        </div>
                                        <div class="col-5">
                                            <label class="font-weight-bold">{{ Str::limit($partido->equipoVisitante->nombre_participante ?? 'Visitante', 15) }}</label>
                                            <input type="number" id="goles_visitante_{{ $partido->id }}" class="form-control text-center mb-2" placeholder="Goles" min="0" required>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number" id="tarjetas_amarillas_visitante_{{ $partido->id }}" class="form-control text-center" placeholder="🟨 Amarillas" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" id="tarjetas_rojas_visitante_{{ $partido->id }}" class="form-control text-center" placeholder="🟥 Rojas" min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info mt-3 p-2 small">
                                        <i class="fas fa-info-circle"></i> Puntos: Victoria = 3, Empate = 1, Derrota = 0
                                    </div>
                                    
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-danger px-4 rounded-pill">
                                            <i class="fas fa-save mr-1"></i> Guardar Resultado
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
        </div>
    </div>
    @endif {{-- fin @else individual --}}
</div>

<script>
var BASE_URL = '{{ url('') }}';

function guardarResultadoDetallado(event, partidoId) {
    event.preventDefault();

    let data = {
        goles_local:                  document.getElementById('goles_local_' + partidoId).value,
        goles_visitante:              document.getElementById('goles_visitante_' + partidoId).value,
        tarjetas_amarillas_local:     parseInt(document.getElementById('tarjetas_amarillas_local_' + partidoId).value) || 0,
        tarjetas_rojas_local:         parseInt(document.getElementById('tarjetas_rojas_local_' + partidoId).value) || 0,
        tarjetas_amarillas_visitante: parseInt(document.getElementById('tarjetas_amarillas_visitante_' + partidoId).value) || 0,
        tarjetas_rojas_visitante:     parseInt(document.getElementById('tarjetas_rojas_visitante_' + partidoId).value) || 0,
    };

    fetch(BASE_URL + '/calificaciones/partido/' + partidoId + '/resultado-grupal', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    }).then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            toastr.error('Error al guardar el resultado');
        }
    }).catch(() => toastr.error('Error de conexión'));
}

document.querySelectorAll('.jornada-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let jornada = this.dataset.jornada;
        document.querySelectorAll('.jornada-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.jornada-content').forEach(c => c.classList.remove('active'));
        document.querySelector(`.jornada-content[data-jornada="${jornada}"]`).classList.add('active');
    });
});

if(document.querySelector('.jornada-btn')) {
    document.querySelector('.jornada-btn').click();
}
</script>
@endsection