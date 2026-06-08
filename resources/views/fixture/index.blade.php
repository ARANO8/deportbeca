{{-- resources/views/fixture/index.blade.php --}}
@extends('layouts.panel')

@section('title', 'Generar Fixture')

@section('styles')
<style>
    .hero-fixture {
        background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
    }
    .event-card {
        background: var(--umsa-surface);
        border-radius: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid var(--umsa-border);
        box-shadow: var(--shadow);
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    .event-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .btn-gradient {
        background: linear-gradient(135deg, var(--umsa-accent) 0%, var(--umsa-accent-dark) 100%);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(192, 57, 43, 0.35);
        color: white;
    }
    [data-theme="dark"] .event-card {
        background: var(--umsa-surface);
        border-color: var(--umsa-border);
    }
    [data-theme="dark"] .event-card h5,
    [data-theme="dark"] .event-card small.font-weight-bold {
        color: var(--umsa-text) !important;
    }
    [data-theme="dark"] .bg-light {
        background: var(--umsa-surface) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="hero-fixture">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="text-white mb-3" style="font-size: 2rem; font-weight: 700;">
                    <i class="fas fa-calendar-alt mr-3"></i>Generador de Fixtures
                </h1>
                <p class="text-white-50 mb-0">
                    Crea calendarios de competencia con algoritmo Round Robin, asigna horarios y visualiza los enfrentamientos.
                </p>
            </div>
            <div class="col-md-4 text-right">
                <i class="fas fa-trophy text-white" style="font-size: 70px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 font-weight-bold">
            <i class="fas fa-calendar-check text-primary mr-2"></i>Eventos Activos
        </h3>
        <a href="{{ route('fixture.mis.fixtures') }}" class="btn btn-info">
            <i class="fas fa-list mr-2"></i>Mis Fixtures
        </a>
    </div>

    <div class="row">
        @forelse($eventos as $evento)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="event-card" @puede('fixture','crear')onclick="verDisciplinas({{ $evento->id }}, '{{ addslashes($evento->nombre) }}')"@endpuede>
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="event-icon mr-3">
                            <i class="fas fa-futbol text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold">{{ $evento->nombre }}</h5>
                            <small class="text-muted">
                                <i class="fas fa-tag mr-1"></i>{{ ucfirst($evento->tipo_evento) }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted"><i class="fas fa-calendar"></i> Inicio</small>
                            <small class="font-weight-bold">{{ $evento->fecha_inicio ? date('d/m/Y', strtotime($evento->fecha_inicio)) : 'Por definir' }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted"><i class="fas fa-flag-checkered"></i> Fin</small>
                            <small class="font-weight-bold">{{ $evento->fecha_fin ? date('d/m/Y', strtotime($evento->fecha_fin)) : 'Por definir' }}</small>
                        </div>
                    </div>
                    
                    @puede('fixture','crear')
                    <button class="btn btn-gradient btn-block">
                        <i class="fas fa-arrow-right mr-2"></i>Generar Fixture
                    </button>
                    @else
                    <span class="badge badge-info">Solo lectura</span>
                    @endpuede
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 bg-light rounded">
                <i class="fas fa-info-circle fa-4x text-muted mb-3"></i>
                <h5>No hay eventos activos</h5>
                <p class="text-muted">Configura un evento para comenzar</p>
                <a href="{{ route('eventos.index') }}" class="btn btn-primary">Configurar Eventos</a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Disciplinas -->
<div class="modal fade" id="disciplinasModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%); border-radius: 16px 16px 0 0;">
                <h5 class="modal-title text-white">
                    <i class="fas fa-dumbbell mr-2"></i>Disciplinas disponibles
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="disciplinasList">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Cargando disciplinas...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
var BASE_URL = '{{ url('') }}';

function verDisciplinas(eventoId, eventoNombre) {
    $('#disciplinasModal').modal('show');
    $.get(BASE_URL + '/fixture/evento/' + eventoId + '/disciplinas', function(data) {
        if(data.length === 0) {
            $('#disciplinasList').html('<div class="text-center py-4"><i class="fas fa-exclamation-triangle fa-3x text-warning"></i><p>No hay disciplinas configuradas</p></div>');
        } else {
            let html = '<div class="list-group">';
            data.forEach(d => {
                html += `<a href="${BASE_URL}/fixture/evento/${eventoId}/disciplina/${d.id}/participantes" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-running text-primary mr-2"></i><strong>${d.nombre}</strong>${d.parent ? `<br><small class="text-muted">${d.parent}</small>` : ''}</div>
                            <i class="fas fa-chevron-right"></i>
                        </a>`;
            });
            html += '</div>';
            $('#disciplinasList').html(html);
        }
    }).fail(() => {
        $('#disciplinasList').html('<div class="text-center py-4 text-danger">Error al cargar</div>');
    });
}
</script>
@endsection
@endsection