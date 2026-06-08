{{-- resources/views/fixture/participantes.blade.php --}}
@extends('layouts.panel')

@section('title', 'Seleccionar Participantes')

@section('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
    }
    .participant-item {
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 12px;
        padding: 15px;
    }
    .participant-item:hover {
        background: #f8f9fa;
        border-color: #667eea;
    }
    .participant-item.selected {
        background: linear-gradient(90deg, #e8f0fe 0%, #fff 100%);
        border-left: 3px solid #667eea;
    }
    .btn-success-gradient {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="text-white mb-2"><i class="fas fa-users mr-3"></i>{{ $disciplina->nombre }}</h1>
                <p class="text-white-50 mb-0">{{ $evento->nombre }} - {{ ucfirst($evento->tipo_evento) }}</p>
            </div>
            <div class="col-md-4 text-right">
                <i class="fas fa-medal text-white" style="font-size: 55px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h3 class="mb-0" id="totalCount">{{ count($participantes) }}</h3>
                <small>Total participantes</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fas fa-user fa-2x text-info mb-2"></i>
                <h3 class="mb-0" id="individualCount">0</h3>
                <small>Individuales</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <i class="fas fa-users fa-2x text-warning mb-2"></i>
                <h3 class="mb-0" id="teamCount">0</h3>
                <small>Grupales</small>
            </div>
        </div>
    </div>

    <div class="card shadow" style="border-radius: 16px;">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-check-square text-primary mr-2"></i>Seleccionar Participantes</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary mr-2" id="selectAllBtn"><i class="fas fa-check-double"></i> Todos</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn"><i class="fas fa-times"></i> Ninguno</button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <form action="{{ route('fixture.guardar.participantes', [$evento->id, $disciplina->id]) }}" method="POST" id="participantesForm">
                @csrf
                @foreach($participantes as $p)
                <div class="participant-item" onclick="toggleCheckbox('part_{{ $p->id }}')">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="part_{{ $p->id }}" name="participantes_ids[]" value="{{ $p->id }}" onchange="updateCounters()">
                        <label class="custom-control-label" for="part_{{ $p->id }}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1 font-weight-bold">{{ $p->nombre_participante }}</h6>
                                    <small class="text-muted">
                                        @if($p->tipo_inscripcion == 'individual')
                                            <i class="fas fa-user text-info"></i> Individual
                                        @else
                                            <i class="fas fa-users text-warning"></i> Grupal ({{ $p->cantidad_integrantes }} integrantes)
                                        @endif
                                    </small>
                                    <div><small class="text-muted"><i class="fas fa-graduation-cap"></i> {{ $p->carrera->nombre ?? $p->facultad->nombre ?? 'Sin institución' }}</small></div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-secondary">ID: {{ $p->id }}</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                @endforeach
                <div class="text-center mt-4">
                    @puede('fixture','crear')
                    <button type="submit" class="btn btn-success-gradient px-5" id="submitBtn" disabled>
                        <i class="fas fa-arrow-right mr-2"></i>Continuar
                    </button>
                    @endpuede
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCheckbox(id) { let cb = document.getElementById(id); cb.checked = !cb.checked; updateCounters(); }
function updateCounters() {
    let ind=0, gru=0, tot=0;
    document.querySelectorAll('input[name="participantes_ids[]"]').forEach(cb => {
        if(cb.checked) {
            tot++;
            if(cb.closest('.participant-item').innerHTML.includes('Individual')) ind++;
            else gru++;
        }
    });
    document.getElementById('totalCount').textContent = tot;
    document.getElementById('individualCount').textContent = ind;
    document.getElementById('teamCount').textContent = gru;
    document.getElementById('submitBtn').disabled = tot === 0;
}
document.getElementById('selectAllBtn').onclick = () => { document.querySelectorAll('input[name="participantes_ids[]"]').forEach(cb => cb.checked = true); updateCounters(); };
document.getElementById('deselectAllBtn').onclick = () => { document.querySelectorAll('input[name="participantes_ids[]"]').forEach(cb => cb.checked = false); updateCounters(); };
updateCounters();
</script>
@endsection