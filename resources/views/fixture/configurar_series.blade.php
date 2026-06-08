{{-- resources/views/fixture/configurar_series.blade.php --}}
@extends('layouts.panel')

@section('title', 'Configurar Series')

@section('styles')
<style>
    .config-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
    }
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .series-config-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
    }
    .series-config-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #667eea;
    }
    .slider-container {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 25px;
    }
    .btn-generate {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        padding: 12px 40px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(17,153,142,0.3);
    }
    .equipo-input {
        width: 80px;
        text-align: center;
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="config-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="text-white mb-2"><i class="fas fa-layer-group mr-3"></i>Configurar Series</h1>
                <p class="text-white-50 mb-0">{{ $disciplina->nombre }} - {{ $total }} participantes seleccionados</p>
            </div>
            <div class="col-md-4 text-right">
                <div class="bg-white rounded-pill px-4 py-2 d-inline-block">
                    <i class="fas fa-random text-primary mr-2"></i>
                    <span class="font-weight-bold">Sorteo Inteligente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h2 class="mb-0">{{ $total }}</h2>
                <small>Total participantes</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                <h2 class="mb-0" id="seriesCount">0</h2>
                <small>Series a crear</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <i class="fas fa-futbol fa-2x text-warning mb-2"></i>
                <h2 class="mb-0" id="partidosCount">0</h2>
                <small>Partidos totales</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <i class="fas fa-calendar-week fa-2x text-info mb-2"></i>
                <h2 class="mb-0" id="jornadasCount">0</h2>
                <small>Jornadas por serie</small>
            </div>
        </div>
    </div>

    <div class="card shadow" style="border-radius: 16px;">
        <div class="card-body">
            <form action="{{ route('fixture.generar', $evento->id) }}" method="POST" id="seriesForm">
                @csrf
                <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
                
                <div class="slider-container mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label class="font-weight-bold mb-2">
                                <i class="fas fa-object-group text-primary mr-2"></i>¿Cuántas series deseas crear?
                            </label>
                            <div class="d-flex align-items-center">
                                <input type="range" name="cantidad_series" id="seriesRange" class="custom-range flex-grow-1" min="1" max="{{ min(8, max(1, $total)) }}" value="{{ min(4, max(1, ceil($total/4))) }}">
                                <span class="ml-3 bg-primary text-white rounded-circle px-3 py-2 font-weight-bold" id="seriesValue">{{ min(4, max(1, ceil($total/4))) }}</span>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle mr-1"></i>
                                Recomendado: {{ ceil($total/6) }} - {{ ceil($total/4) }} series
                            </small>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3 text-center">
                                <div class="row">
                                    <div class="col-4">
                                        <i class="fas fa-users text-primary fa-lg"></i>
                                        <div><small>Por serie</small></div>
                                        <h4 class="mb-0 text-primary" id="porSerie">0</h4>
                                    </div>
                                    <div class="col-4">
                                        <i class="fas fa-futbol text-success fa-lg"></i>
                                        <div><small>Partidos/serie</small></div>
                                        <h4 class="mb-0 text-success" id="partidosPorSerie">0</h4>
                                    </div>
                                    <div class="col-4">
                                        <i class="fas fa-trophy text-warning fa-lg"></i>
                                        <div><small>Clasifican</small></div>
                                        <h4 class="mb-0 text-warning" id="clasificanInfo">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="seriesContainer"></div>
                
                <div class="text-center mt-4 pt-3 border-top">
                    @puede('fixture','crear')
                    <button type="submit" class="btn btn-generate btn-lg text-white">
                        <i class="fas fa-magic mr-2"></i>Generar Fixture
                    </button>
                    @endpuede
                    <a href="{{ route('fixture.index') }}" class="btn btn-secondary btn-lg ml-3">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const range = document.getElementById('seriesRange');
const valueSpan = document.getElementById('seriesValue');
const seriesCountSpan = document.getElementById('seriesCount');
const partidosCountSpan = document.getElementById('partidosCount');
const jornadasSpan = document.getElementById('jornadasCount');
const porSerieSpan = document.getElementById('porSerie');
const partidosPorSerieSpan = document.getElementById('partidosPorSerie');
const clasificanSpan = document.getElementById('clasificanInfo');
const total = {{ $total }};
const lugares = @json($lugares);

function calcStats(series) {
    if(series === 0) series = 1;
    let ps = Math.ceil(total / series);
    let pps = (ps * (ps - 1)) / 2;
    let tp = series * pps;
    let j = ps - 1;
    seriesCountSpan.textContent = series;
    partidosCountSpan.textContent = tp;
    jornadasSpan.textContent = j;
    porSerieSpan.textContent = ps;
    partidosPorSerieSpan.textContent = pps;
    clasificanSpan.textContent = Math.min(2, ps);
    return {ps, pps, tp, j};
}

function genSeries(series) {
    calcStats(series);
    let letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    let html = '<h4 class="mb-4"><i class="fas fa-calendar-alt text-primary mr-2"></i>Configuración de cada serie</h4>';
    html += '<div class="row">';
    
    for(let i = 0; i < series; i++) {
        let ps = Math.ceil(total / series);
        let restantes = total;
        for(let j = 0; j < i; j++) {
            restantes -= Math.ceil(total / series);
        }
        let equiposEstaSerie = Math.min(ps, restantes);
        
        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="series-config-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary mb-0">
                            <i class="fas fa-trophy mr-2"></i>Serie ${letras[i]}
                        </h5>
                        <div class="form-group mb-0" style="width: 120px;">
                            <label class="small text-muted d-block">Equipos en esta serie</label>
                            <input type="number" 
                                   name="equipos_serie_${i}" 
                                   id="equipos_serie_${i}"
                                   class="form-control form-control-sm text-center equipo-input" 
                                   value="${equiposEstaSerie}"
                                   min="2"
                                   max="${total - (series - 1) * 2}"
                                   onchange="recalcularDistribucion(${series})">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold"><i class="fas fa-flag-checkered"></i> ¿Cuántos clasifican?</label>
                        <select name="cuantos_clasifican[${i}]" class="form-control">
                            <option value="1">1 equipo</option>
                            <option value="2" selected>2 equipos</option>
                            <option value="3">3 equipos</option>
                            <option value="4">4 equipos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold"><i class="fas fa-map-marker-alt"></i> Lugar de juego</label>
                        <select name="lugar_serie_${i}" class="form-control">
                            <option value="">📌 Seleccionar lugar</option>
                            ${lugares.map(l => `<option value="${l.id}">🏟️ ${l.nombre}</option>`).join('')}
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="small font-weight-bold"><i class="fas fa-calendar"></i> Fecha</label>
                                <input type="date" name="fecha_serie_${i}" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="small font-weight-bold"><i class="fas fa-clock"></i> Hora</label>
                                <input type="time" name="hora_serie_${i}" class="form-control" value="08:00">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-2 p-2 small">
                        <i class="fas fa-info-circle mr-1"></i> 
                        <strong>Formato:</strong> Todos contra todos (Round Robin) | 
                        <strong>Partidos:</strong> <span id="partidos_serie_${i}">0</span> partidos
                    </div>
                </div>
            </div>
        `;
    }
    html += '</div>';
    document.getElementById('seriesContainer').innerHTML = html;
    
    // Actualizar contadores de partidos por serie
    for(let i = 0; i < series; i++) {
        let equipos = document.getElementById(`equipos_serie_${i}`).value;
        let partidos = (equipos * (equipos - 1)) / 2;
        document.getElementById(`partidos_serie_${i}`).innerHTML = partidos;
    }
}

function recalcularDistribucion(series) {
    let totalEquipos = {{ $total }};
    let suma = 0;
    let valores = [];
    
    for(let i = 0; i < series; i++) {
        let val = parseInt(document.getElementById(`equipos_serie_${i}`).value) || 2;
        valores[i] = val;
        suma += val;
    }
    
    if(suma !== totalEquipos) {
        let diferencia = totalEquipos - suma;
        for(let i = 0; i < series && diferencia !== 0; i++) {
            if(diferencia > 0 && valores[i] < totalEquipos) {
                valores[i]++;
                diferencia--;
            } else if(diferencia < 0 && valores[i] > 2) {
                valores[i]--;
                diferencia++;
            }
        }
        
        for(let i = 0; i < series; i++) {
            document.getElementById(`equipos_serie_${i}`).value = valores[i];
            let partidos = (valores[i] * (valores[i] - 1)) / 2;
            document.getElementById(`partidos_serie_${i}`).innerHTML = partidos;
        }
    }
}

range.addEventListener('input', function() {
    let v = parseInt(this.value);
    valueSpan.textContent = v;
    genSeries(v);
});

genSeries(parseInt(range.value));
</script>
@endsection