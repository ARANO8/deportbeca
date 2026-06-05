@extends('layouts.panel')

@section('title', 'Dashboard')

@section('styles')
<style>
    :root {
        --primary: #5e72e4;
        --success: #2dce89;
        --warning: #fb6340;
        --danger:  #f5365c;
        --info:    #11cdef;
        --dark:    #212529;
        --gray:    #6c757d;
        --border:  #e9ecef;
    }
    .kpi-card {
        border-radius: 14px; border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        transition: transform .2s, box-shadow .2s; overflow: hidden;
    }
    .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.13); }
    .kpi-icon {
        width: 52px; height: 52px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; flex-shrink: 0;
    }
    .kpi-value { font-size: 2rem; font-weight: 800; line-height: 1; color: var(--dark); }
    .kpi-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: var(--gray); font-weight: 600; }
    .kpi-sub   { font-size: .78rem; color: var(--gray); margin-top: 4px; }
    .kpi-sub span { font-weight: 700; }
    .panel-card {
        border-radius: 14px; border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        overflow: hidden; background: #fff;
    }
    .panel-card .card-header {
        background: #f8f9fa; border-bottom: 1px solid var(--border);
        padding: 14px 18px; font-weight: 700; font-size: .85rem;
    }
    .panel-card .card-body { padding: 18px; }
    .mini-progress { height: 5px; border-radius: 10px; background: var(--border); overflow: hidden; margin-top: 6px; }
    .mini-progress-fill { height: 100%; border-radius: 10px; }
    .alert-serie {
        background: #fff8f0; border: 1px solid #ffd6b0;
        border-radius: 10px; padding: 12px 14px; margin-bottom: 10px;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .alert-serie .badge-pend {
        background: #fb6340; color: #fff; border-radius: 20px;
        padding: 2px 10px; font-size: .72rem; font-weight: 700; white-space: nowrap;
    }
    .alert-serie .serie-info { font-size: .82rem; color: var(--dark); }
    .alert-serie .serie-info small { display: block; color: var(--gray); font-size: .72rem; }
    .preinsc-row {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .preinsc-row:last-child { border-bottom: none; }
    .preinsc-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .85rem; flex-shrink: 0;
    }
    .preinsc-name { font-size: .85rem; font-weight: 600; color: var(--dark); }
    .preinsc-meta { font-size: .72rem; color: var(--gray); }
    .badge-tipo { font-size: .65rem; padding: 2px 8px; border-radius: 20px; font-weight: 700; }
    .badge-grupal     { background: #e8f5e9; color: #2e7d32; }
    .badge-individual { background: #e3f2fd; color: #1565c0; }
    .partido-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .partido-item:last-child { border-bottom: none; }
    .partido-vs       { font-size: .75rem; font-weight: 800; color: var(--gray); }
    .partido-equipos  { font-size: .82rem; font-weight: 600; }
    .partido-meta     { font-size: .72rem; color: var(--gray); }
    .evento-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff; border-radius: 30px; padding: 5px 14px;
        font-size: .75rem; font-weight: 700; margin: 0 6px 6px 0;
    }
    .evento-chip .dot { width: 7px; height: 7px; border-radius: 50%; background: #a0e9c7; }
    .empty-state { text-align: center; padding: 28px 0; color: var(--gray); }
    .empty-state i { font-size: 2.5rem; opacity: .3; display: block; margin-bottom: 8px; }
    .empty-state p { font-size: .82rem; }
</style>
@endsection

@section('content')
<div class="container-fluid pb-4">

    {{-- BIENVENIDA --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap:10px;">
        <div>
            <h4 class="mb-0 font-weight-bold" style="color:#212529;">
                Bienvenido, {{ auth()->user()->name }}
            </h4>
            <small class="text-muted">
                {{ auth()->user()->rol->nombre ?? ucfirst(auth()->user()->role ?? 'Usuario') }}
                &nbsp;&middot;&nbsp; {{ now()->format('d/m/Y') }}
            </small>
        </div>
        @if($eventosActivos->isNotEmpty())
        <div>
            @foreach($eventosActivos as $ev)
                <span class="evento-chip">
                    <span class="dot"></span> {{ $ev->nombre }}
                </span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- KPI CARDS --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center py-3 px-4" style="gap:16px;">
                    <div class="kpi-icon" style="background:#fff3ec;">
                        <i class="fas fa-clock" style="color:#fb6340;"></i>
                    </div>
                    <div>
                        <div class="kpi-value" style="color:#fb6340;">{{ $preinscPendientes }}</div>
                        <div class="kpi-label">Pendientes</div>
                        <div class="kpi-sub">de <span>{{ $totalPreinsc }}</span> pre-inscripciones</div>
                    </div>
                </div>
                @php $pctPend = $totalPreinsc > 0 ? round($preinscPendientes / $totalPreinsc * 100) : 0; @endphp
                <div class="mini-progress mx-4 mb-3"><div class="mini-progress-fill" style="width:{{ $pctPend }}%; background:#fb6340;"></div></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center py-3 px-4" style="gap:16px;">
                    <div class="kpi-icon" style="background:#eafaf1;">
                        <i class="fas fa-check-circle" style="color:#2dce89;"></i>
                    </div>
                    <div>
                        <div class="kpi-value" style="color:#2dce89;">{{ $preinscHabilitadas }}</div>
                        <div class="kpi-label">Habilitados</div>
                        <div class="kpi-sub"><span>{{ $preinscObservadas }}</span> observados</div>
                    </div>
                </div>
                @php $pctHab = $totalPreinsc > 0 ? round($preinscHabilitadas / $totalPreinsc * 100) : 0; @endphp
                <div class="mini-progress mx-4 mb-3"><div class="mini-progress-fill" style="width:{{ $pctHab }}%; background:#2dce89;"></div></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center py-3 px-4" style="gap:16px;">
                    <div class="kpi-icon" style="background:#eef2ff;">
                        <i class="fas fa-trophy" style="color:#5e72e4;"></i>
                    </div>
                    <div>
                        <div class="kpi-value" style="color:#5e72e4;">{{ $seriesEnCurso }}</div>
                        <div class="kpi-label">Series en curso</div>
                        <div class="kpi-sub"><span>{{ $seriesFinalizadas }}</span> finalizadas</div>
                    </div>
                </div>
                @php $ts = $seriesEnCurso + $seriesFinalizadas; $pctSer = $ts > 0 ? round($seriesFinalizadas / $ts * 100) : 0; @endphp
                <div class="mini-progress mx-4 mb-3"><div class="mini-progress-fill" style="width:{{ $pctSer }}%; background:#5e72e4;"></div></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center py-3 px-4" style="gap:16px;">
                    <div class="kpi-icon" style="background:#fff0f3;">
                        <i class="fas fa-futbol" style="color:#f5365c;"></i>
                    </div>
                    <div>
                        <div class="kpi-value" style="color:#f5365c;">{{ $partidosPendientes }}</div>
                        <div class="kpi-label">Partidos pendientes</div>
                        <div class="kpi-sub"><span>{{ $partidosFinalizados }}</span> / {{ $totalPartidos }} finalizados</div>
                    </div>
                </div>
                @php $pctPart = $totalPartidos > 0 ? round($partidosFinalizados / $totalPartidos * 100) : 0; @endphp
                <div class="mini-progress mx-4 mb-3"><div class="mini-progress-fill" style="width:{{ $pctPart }}%; background:#f5365c;"></div></div>
            </div>
        </div>
    </div>

    {{-- GRÁFICOS --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-chart-pie mr-2" style="color:#5e72e4;"></i>Pre-inscripciones por estado</span>
                    <a href="{{ route('archivador.index') }}" class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:.7rem;">Ver todas</a>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if($totalPreinsc > 0)
                        <div style="width:200px; height:200px; flex-shrink:0;">
                            <canvas id="chartEstados"></canvas>
                        </div>
                        <div class="mt-3 w-100">
                            @foreach([['Pendientes',$preinscPendientes,'#fb6340'],['Habilitados',$preinscHabilitadas,'#2dce89'],['Observados',$preinscObservadas,'#f5365c']] as [$lbl,$val,$col])
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:.8rem;">
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{$col}};margin-right:6px;"></span>{{$lbl}}</span>
                                <strong>{{$val}}</strong>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state"><i class="fas fa-inbox"></i><p>Sin pre-inscripciones aún</p></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-6 mb-3">
            <div class="panel-card h-100">
                <div class="card-header">
                    <i class="fas fa-chart-bar mr-2" style="color:#5e72e4;"></i>Participantes por disciplina
                </div>
                <div class="card-body">
                    @if(count($graficoDisciplina['labels']) > 0)
                        <canvas id="chartDisciplina" height="160"></canvas>
                    @else
                        <div class="empty-state"><i class="fas fa-dumbbell"></i><p>Sin datos por disciplina aún</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTAS + PENDIENTES --}}
    <div class="row mb-4">
        <div class="col-xl-6 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>
                        <i class="fas fa-exclamation-triangle mr-2" style="color:#fb6340;"></i>
                        Series con resultados pendientes
                        @if($seriesConPendientes->isNotEmpty())
                            <span class="badge badge-pill badge-warning ml-1">{{ $seriesConPendientes->count() }}</span>
                        @endif
                    </span>
                    <a href="{{ route('fixture.mis.fixtures') }}" class="btn btn-sm btn-outline-warning rounded-pill" style="font-size:.7rem;">Ver fixture</a>
                </div>
                <div class="card-body">
                    @forelse($seriesConPendientes as $serie)
                        <div class="alert-serie">
                            <div class="serie-info">
                                <strong>{{ $serie->nombre_serie }}</strong>
                                <small>{{ $serie->disciplina->nombre ?? '—' }} &middot; {{ $serie->eventoConfiguracion->nombre ?? '—' }}</small>
                            </div>
                            <div class="d-flex align-items-center" style="gap:8px;">
                                <span class="badge-pend">{{ $serie->pendientes_count }} pendiente{{ $serie->pendientes_count !== 1 ? 's' : '' }}</span>
                                <a href="{{ route('fixture.ver.serie', $serie->id) }}" class="btn btn-sm btn-light rounded-pill px-2 py-1" style="font-size:.7rem;"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-check-double" style="color:#2dce89; opacity:1;"></i>
                            <p style="color:#2dce89; font-weight:600;">Todos los partidos tienen resultado</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>
                        <i class="fas fa-user-clock mr-2" style="color:#5e72e4;"></i>
                        Pendientes de revisión
                        @if($recientesPendientes->isNotEmpty())
                            <span class="badge badge-pill badge-primary ml-1">{{ $recientesPendientes->count() }}</span>
                        @endif
                    </span>
                    <a href="{{ route('archivador.index') }}" class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:.7rem;">Revisar</a>
                </div>
                <div class="card-body">
                    @forelse($recientesPendientes as $p)
                        @php
                            $initials = strtoupper(substr($p->nombre_participante, 0, 1));
                            $colores  = ['#5e72e4','#2dce89','#fb6340','#f5365c','#11cdef','#764ba2'];
                            $color    = $colores[$loop->index % count($colores)];
                        @endphp
                        <div class="preinsc-row">
                            <div class="preinsc-avatar" style="background:{{ $color }}20; color:{{ $color }};">{{ $initials }}</div>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="preinsc-name text-truncate">{{ $p->nombre_participante }}</div>
                                <div class="preinsc-meta">{{ $p->disciplina->nombre ?? '—' }} &middot; {{ $p->carrera->nombre ?? $p->facultad->nombre ?? '—' }}</div>
                            </div>
                            <div class="d-flex align-items-center" style="gap:6px;">
                                <span class="badge-tipo {{ $p->tipo_inscripcion === 'grupal' ? 'badge-grupal' : 'badge-individual' }}">{{ ucfirst($p->tipo_inscripcion) }}</span>
                                <a href="{{ route('archivador.show', $p->id) }}" class="btn btn-sm btn-light rounded-pill px-2 py-1" style="font-size:.7rem;"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state"><i class="fas fa-inbox"></i><p>No hay pre-inscripciones pendientes</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- PRÓXIMOS PARTIDOS --}}
    @if($proximosPartidos->isNotEmpty())
    <div class="row mb-2">
        <div class="col-12">
            <div class="panel-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt mr-2" style="color:#5e72e4;"></i>Próximos partidos programados
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($proximosPartidos as $partido)
                        <div class="col-xl-4 col-md-6 mb-1">
                            <div class="partido-item">
                                <div style="min-width:46px; text-align:center;">
                                    <div style="font-size:.68rem; color:var(--gray); text-transform:uppercase; font-weight:700;">{{ optional($partido->fecha)->format('M') }}</div>
                                    <div style="font-size:1.3rem; font-weight:800; line-height:1; color:var(--dark);">{{ optional($partido->fecha)->format('d') ?? '—' }}</div>
                                </div>
                                <div class="flex-grow-1" style="min-width:0;">
                                    <div class="partido-equipos text-truncate">
                                        {{ $partido->equipoLocal->nombre_participante ?? 'Local' }}
                                        <span class="partido-vs mx-1">VS</span>
                                        {{ $partido->equipoVisitante->nombre_participante ?? 'Visit.' }}
                                    </div>
                                    <div class="partido-meta">
                                        {{ $partido->serie->disciplina->nombre ?? '—' }}
                                        @if($partido->hora_inicio) &middot; {{ substr($partido->hora_inicio, 0, 5) }} hrs @endif
                                        @if($partido->lugar) &middot; {{ $partido->lugar->nombre }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
(function () {

  function isDarkMode() {
    return document.documentElement.getAttribute('data-theme') === 'dark';
  }

  function chartTokens(dark) {
    return {
      grid:    dark ? 'rgba(255,255,255,0.07)' : '#f0f0f0',
      tick:    dark ? '#9CA3AF'               : '#6c757d',
      border:  dark ? '#1F2937'               : '#ffffff',
      barBg:   dark ? 'rgba(94,114,228,0.28)' : 'rgba(94,114,228,0.15)',
    };
  }

  var chartEstados    = null;
  var chartDisciplina = null;

  @if($totalPreinsc > 0)
  chartEstados = new Chart(document.getElementById('chartEstados'), {
    type: 'doughnut',
    data: {
      labels: @json($distribucionEstados['labels']),
      datasets: [{
        data: @json($distribucionEstados['data']),
        backgroundColor: @json($distribucionEstados['colors']),
        borderWidth: 3,
        borderColor: chartTokens(isDarkMode()).border
      }]
    },
    options: {
      cutout: '68%',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      animation: { duration: 800 }
    }
  });
  @endif

  @if(count($graficoDisciplina['labels']) > 0)
  var tk = chartTokens(isDarkMode());
  chartDisciplina = new Chart(document.getElementById('chartDisciplina'), {
    type: 'bar',
    data: {
      labels: @json($graficoDisciplina['labels']),
      datasets: [{
        label: 'Participantes',
        data: @json($graficoDisciplina['data']),
        backgroundColor: tk.barBg,
        borderColor: '#5e72e4',
        borderWidth: 2,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, font: { size: 11 }, color: tk.tick },
          grid:  { color: tk.grid }
        },
        x: {
          ticks: { font: { size: 11 }, color: tk.tick },
          grid:  { display: false }
        }
      },
      animation: { duration: 800 }
    }
  });
  @endif

  // Actualizar charts cuando cambia el tema
  document.addEventListener('umsa-theme-change', function (e) {
    var dark = e.detail.theme === 'dark';
    var tk   = chartTokens(dark);

    if (chartEstados) {
      chartEstados.data.datasets[0].borderColor = tk.border;
      chartEstados.update('none');
    }

    if (chartDisciplina) {
      chartDisciplina.data.datasets[0].backgroundColor = tk.barBg;
      chartDisciplina.options.scales.y.ticks.color = tk.tick;
      chartDisciplina.options.scales.y.grid.color  = tk.grid;
      chartDisciplina.options.scales.x.ticks.color = tk.tick;
      chartDisciplina.update('none');
    }
  });

}());
</script>
@endsection
