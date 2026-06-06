@extends('layouts.panel')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <h4 class="mb-0"><i class="fas fa-archive"></i> Inscripcion Equipo</h4>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-success fs-6">H: {{ $habilitados->count() }}</span>
                    <span class="badge bg-warning fs-6">O: {{ $observados->count() }}</span>
                    <span class="badge bg-dark fs-6">P: {{ $pendientes->count() }}</span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-2 p-md-3">
            <form method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-12 col-md-4">
                        <select name="tipo_evento" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="intercarreras" {{ $tipoEvento=='intercarreras'?'selected':'' }}>🏆 Intercarreras</option>
                            <option value="olimpiadas" {{ $tipoEvento=='olimpiadas'?'selected':'' }}>🏅 Olimpiadas</option>
                            <option value="interauxiliares" {{ $tipoEvento=='interauxiliares'?'selected':'' }}>🤝 Interauxiliares</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="disciplina_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todas las disciplinas</option>
                            @foreach($disciplinas as $d)
                                <optgroup label="{{ $d->nombre }}">
                                    <option value="{{ $d->id }}" {{ $disciplinaId==$d->id?'selected':'' }}>{{ $d->nombre }}</option>
                                    @foreach($d->subDisciplines as $sub)
                                        <option value="{{ $sub->id }}" {{ $disciplinaId==$sub->id?'selected':'' }}>↳ {{ $sub->nombre }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <a href="{{ route('archivador.index') }}" class="btn btn-secondary btn-sm flex-fill">Refrescar</a>
                        {{-- Exportar Excel con los filtros actuales --}}
                        <a href="{{ route('exportar.preinscripciones.excel', array_filter(['tipo_evento' => $tipoEvento, 'disciplina_id' => $disciplinaId ?? null])) }}"
                           class="btn btn-success btn-sm"
                           title="Exportar a Excel">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </form>
            
            <div class="mb-4">
                <h5 class="text-success"><i class="fas fa-check-circle"></i> Equipos Habilitados  ({{ $habilitados->count() }})</h5>
                <div class="row g-2">
                    @forelse($habilitados as $e)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white py-1">
                                    <strong>{{ $e->nombre_equipo ?: 'Individual' }}</strong>
                                    <small class="float-end">{{ $e->codigo_inscripcion }}</small>
                                </div>
                                <div class="card-body p-2">
                                    <p class="mb-1"><strong>Disciplina:</strong> {{ $e->disciplina->nombre }}</p>
                                    <p class="mb-1"><strong>Capitán:</strong> {{ $e->representante_nombre }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $e->representante_email }}</p>
                                    @if($e->facultad_id)
                                        <p class="mb-1"><strong>Facultad:</strong> {{ $e->facultad->nombre ?? 'N/A' }}</p>
                                    @endif
                                    @if($e->carrera_id)
                                        <p class="mb-1"><strong>Carrera:</strong> {{ $e->carrera->nombre ?? 'N/A' }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ route('archivador.show', $e->id) }}" class="btn btn-sm btn-info w-100">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info py-2">No hay equipos habilitados</div></div>
                    @endforelse
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="text-warning"><i class="fas fa-exclamation-triangle"></i> Equipos Observados ({{ $observados->count() }})</h5>
                <div class="row g-2">
                    @forelse($observados as $e)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning py-1">
                                    <strong>{{ $e->nombre_equipo ?: 'Individual' }}</strong>
                                    <small class="float-end">{{ $e->codigo_inscripcion }}</small>
                                </div>
                                <div class="card-body p-2">
                                    <p class="mb-1"><strong>Motivo:</strong> <span class="text-danger">{{ \Illuminate\Support\Str::limit($e->observaciones, 60) }}</span></p>
                                    <div class="mt-2">
                                        <a href="{{ route('archivador.show', $e->id) }}" class="btn btn-sm btn-info w-100">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info py-2">No hay equipos observados</div></div>
                    @endforelse
                </div>
            </div>
            
            <div>
                <h5 class="text-dark"><i class="fas fa-clock"></i> Equipos Pendientes ({{ $pendientes->count() }})</h5>
                <div class="row g-2">
                    @forelse($pendientes as $e)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-dark h-100">
                                <div class="card-header bg-dark text-white py-1">
                                    {{ $e->nombre_equipo ?: 'Individual' }}
                                    <small class="float-end">{{ $e->codigo_inscripcion }}</small>
                                </div>
                                <div class="card-body p-2">
                                    <p class="mb-1"><strong>Disciplina:</strong> {{ $e->disciplina->nombre }}</p>
                                    <p class="mb-1"><strong>Capitán:</strong> {{ $e->representante_nombre }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $e->representante_email }}</p>
                                    <p class="mb-1"><strong>Fecha:</strong> {{ $e->created_at->format('d/m/Y H:i') }}</p>
                                    <div class="mt-2">
                                        <a href="{{ route('archivador.show', $e->id) }}" class="btn btn-sm btn-info w-100">Revisar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info py-2">No hay equipos pendientes</div></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function habilitar(id) { 
    if(confirm('¿Habilitar este equipo?')) {
        fetch(`/archivador/${id}/habilitar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) { alert(data.message); location.reload(); }
            else { alert('Error: ' + data.message); }
        })
        .catch(error => { alert('Error de conexión'); });
    } 
}

function revertir(id) { 
    if(confirm('¿Revertir a pendiente?')) {
        fetch(`/archivador/${id}/revertir`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) { alert(data.message); location.reload(); }
            else { alert('Error: ' + data.message); }
        })
        .catch(error => { alert('Error de conexión'); });
    } 
}

function observar(id) {
    let motivo = prompt('Motivo de la observación:');
    if(motivo && motivo.length >= 10) {
        fetch(`/archivador/${id}/observar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({motivo_observacion: motivo})
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) { alert(data.message); location.reload(); }
            else { alert('Error: ' + data.message); }
        })
        .catch(error => { alert('Error de conexión'); });
    } else if(motivo) {
        alert('El motivo debe tener al menos 10 caracteres');
    }
} 
</script>

<style>
    @media (max-width: 576px) {
        .card-header h4 { font-size: 1.2rem; }
        .badge.fs-6 { font-size: 0.75rem !important; }
        .btn-sm { font-size: 0.7rem; }
        .card-body p { font-size: 0.8rem; margin-bottom: 0.3rem; }
    }
</style>
@endsection