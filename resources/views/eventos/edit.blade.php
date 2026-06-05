@extends('layouts.panel')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%) !important; border-radius: 16px 16px 0 0;">
            <h3 class="text-white mb-0"><i class="fas fa-cog mr-2"></i>Configurar {{ ucfirst($tipoEvento) }}</h3>
            <a href="{{ route('eventos.index') }}" class="btn btn-light btn-sm float-end">Volver</a>
        </div>
        <form method="POST" action="{{ route('eventos.update', $tipoEvento) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label>Nombre del Evento *</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $configuracion->nombre ?? ucfirst($tipoEvento)) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Código de Acceso</label>
                            <input type="text" name="codigo_acceso" class="form-control" value="{{ old('codigo_acceso', $configuracion->codigo_acceso) }}" placeholder="Dejar vacío para auto-generar">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $configuracion->descripcion) }}</textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="{{ $configuracion->fecha_inicio?->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Fecha de Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ $configuracion->fecha_fin?->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="activo" class="form-check-input" id="activo" value="1" {{ $configuracion->activo ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Activar este evento</label>
                    </div>
                </div>
                
                <!-- SECCIÓN DE DISCIPLINAS ESTILO TABLERO -->
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="fs-5 fw-bold">
                            <i class="fas fa-medal text-warning"></i> Disciplinas Permitidas
                        </label>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllDisciplinas">
                                <i class="fas fa-check-double"></i> Seleccionar todas
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllDisciplinas">
                                <i class="fas fa-times"></i> Limpiar todo
                            </button>
                        </div>
                    </div>
                    
                    <div class="border rounded p-3 disciplinas-scroll" style="max-height: 500px; overflow-y: auto;">
                        @foreach($disciplinas as $disciplina)
                            <div class="col-12 mb-3">
                                <div class="disciplinary-group">
                                    <div class="disciplinary-header mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                @if($disciplina->subDisciplines->count() > 0)
                                                    <span class="badge bg-warning text-dark p-2">
                                                        <i class="fas fa-folder-open"></i> {{ $disciplina->nombre }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-primary p-2">
                                                        <i class="fas fa-trophy"></i> {{ $disciplina->nombre }}
                                                    </span>
                                                @endif
                                                <span class="badge bg-secondary">{{ $disciplina->codigo }}</span>
                                            </h6>
                                            @if($disciplina->subDisciplines->count() > 0)
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-link text-primary select-group" data-parent="{{ $disciplina->id }}">
                                                        <i class="fas fa-check-square"></i> Todo
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-link text-secondary clear-group" data-parent="{{ $disciplina->id }}">
                                                        <i class="fas fa-square"></i> Limpiar
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="disciplinary-body">
                                        @if($disciplina->subDisciplines->count() > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($disciplina->subDisciplines as $sub)
                                                    <div class="badge-item">
                                                        <input type="checkbox" 
                                                               name="disciplinas_ids[]" 
                                                               class="btn-check sub-checkbox" 
                                                               id="sub_{{ $sub->id }}" 
                                                               value="{{ $sub->id }}"
                                                               data-parent-id="{{ $disciplina->id }}"
                                                               autocomplete="off"
                                                               {{ in_array($sub->id, $configuracion->disciplinas_ids ?? []) ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success rounded-pill" for="sub_{{ $sub->id }}">
                                                            <i class="fas fa-tag"></i> {{ $sub->nombre }}
                                                            <span class="badge bg-light text-dark ms-1">{{ $sub->codigo }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="badge-item d-inline-block">
                                                <input type="checkbox" 
                                                       name="disciplinas_ids[]" 
                                                       class="btn-check disciplina-checkbox" 
                                                       id="disciplina_{{ $disciplina->id }}" 
                                                       value="{{ $disciplina->id }}"
                                                       autocomplete="off"
                                                       {{ in_array($disciplina->id, $configuracion->disciplinas_ids ?? []) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary rounded-pill px-4" for="disciplina_{{ $disciplina->id }}">
                                                    <i class="fas fa-check-circle"></i> {{ $disciplina->nombre }}
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-3">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 p-3 rounded disciplinas-stats">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <h3 class="mb-0 text-primary" id="totalSelectedStats">0</h3>
                                    <small class="text-muted">Disciplinas seleccionadas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <h3 class="mb-0 text-success" id="totalSubSelectedStats">0</h3>
                                    <small class="text-muted">Subdisciplinas seleccionadas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <h3 class="mb-0 text-warning" id="totalGroupsStats">{{ $disciplinas->count() }}</h3>
                                    <small class="text-muted">Grupos disponibles</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Nota:</strong> Las disciplinas que tienen subdisciplinas NO pueden seleccionarse directamente. 
                        Debe seleccionar las subdisciplinas específicas.
                    </small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Mínimo Integrantes (Grupal)</label>
                            <input type="number" name="min_integrantes_grupal" class="form-control" value="{{ $configuracion->min_integrantes_grupal ?? 4 }}" min="1" max="20">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Máximo Integrantes (Grupal)</label>
                            <input type="number" name="max_integrantes_grupal" class="form-control" value="{{ $configuracion->max_integrantes_grupal ?? 8 }}" min="1" max="20">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Configuración</button>
                <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    
    @if($configuracion->exists)
    <div class="card mt-3">
        <div class="card-header" style="background: linear-gradient(135deg, var(--umsa-primary) 0%, var(--umsa-primary-dark) 100%) !important;">
            <h5 class="text-white mb-0"><i class="fas fa-share-alt mr-2"></i>Compartir este codigo</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-success text-center">
                <h3><code>{{ $configuracion->codigo_acceso }}</code></h3>
                <p>Comparta este código con los representantes para que puedan inscribirse</p>
                <button class="btn btn-sm btn-success" onclick="copiarCodigo('{{ $configuracion->codigo_acceso }}')">
                    <i class="fas fa-copy"></i> Copiar código
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function copiarCodigo(codigo) {
    navigator.clipboard.writeText(codigo);
    Swal.fire({
        icon: 'success',
        title: '¡Copiado!',
        text: 'Código copiado al portapapeles',
        timer: 2000,
        showConfirmButton: false
    });
}

function updateStats() {
    const allChecked = document.querySelectorAll('input[name="disciplinas_ids[]"]:checked');
    const total = allChecked.length;
    
    // Contar subdisciplinas
    const subDisciplines = document.querySelectorAll('.sub-checkbox:checked');
    
    document.getElementById('totalSelectedStats').textContent = total;
    document.getElementById('totalSubSelectedStats').textContent = subDisciplines.length;
}

document.addEventListener('DOMContentLoaded', function() {
    updateStats();
    
    // Actualizar estadísticas cuando cambie cualquier checkbox
    document.querySelectorAll('input[name="disciplinas_ids[]"]').forEach(cb => {
        cb.addEventListener('change', updateStats);
    });
    
    // Seleccionar todas las subdisciplinas de un grupo
    document.querySelectorAll('.select-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const parentId = this.dataset.parent;
            document.querySelectorAll(`.sub-checkbox[data-parent-id="${parentId}"]`).forEach(cb => cb.checked = true);
            updateStats();
        });
    });
    
    // Limpiar todas las subdisciplinas de un grupo
    document.querySelectorAll('.clear-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const parentId = this.dataset.parent;
            document.querySelectorAll(`.sub-checkbox[data-parent-id="${parentId}"]`).forEach(cb => cb.checked = false);
            updateStats();
        });
    });
    
    // Seleccionar todas las disciplinas
    document.getElementById('selectAllDisciplinas')?.addEventListener('click', function() {
        document.querySelectorAll('input[name="disciplinas_ids[]"]').forEach(cb => cb.checked = true);
        updateStats();
    });
    
    // Limpiar todas las disciplinas
    document.getElementById('deselectAllDisciplinas')?.addEventListener('click', function() {
        document.querySelectorAll('input[name="disciplinas_ids[]"]').forEach(cb => cb.checked = false);
        updateStats();
    });
});
</script>

<style>
.disciplinary-group {
    padding: 10px;
    border-radius: 12px;
    transition: all 0.3s ease;
}
.disciplinary-group:hover {
    background: rgba(13, 110, 253, 0.05);
}
.badge-item {
    transition: transform 0.2s ease;
}
.badge-item:hover {
    transform: translateY(-2px);
}
.btn-check:checked + .btn-outline-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-color: transparent;
}
.btn-check:checked + .btn-outline-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-color: transparent;
}
.stat-card {
    padding: 10px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.btn-xs {
    padding: 0.1rem 0.5rem;
    font-size: 0.75rem;
}
.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
}
.disciplinas-scroll {
    background: var(--umsa-bg);
    border-color: var(--umsa-border) !important;
}
.disciplinas-stats {
    background: var(--umsa-bg);
    border: 1px solid var(--umsa-border);
}

/* ---- DARK MODE ---- */
[data-theme="dark"] .disciplinas-scroll {
    background: rgba(255,255,255,0.03);
    border-color: var(--umsa-border) !important;
}
[data-theme="dark"] .disciplinas-stats {
    background: rgba(255,255,255,0.03);
}
[data-theme="dark"] .stat-card {
    background: var(--umsa-surface);
    box-shadow: 0 2px 6px rgba(0,0,0,0.35);
    color: var(--umsa-text);
}
[data-theme="dark"] .stat-card h3 { color: var(--umsa-primary-light) !important; }
[data-theme="dark"] .disciplinary-group:hover {
    background: rgba(26,82,118,0.1);
}
[data-theme="dark"] .btn-check:not(:checked) + .btn-outline-success {
    border-color: var(--umsa-border);
    color: var(--umsa-text-secondary);
}
[data-theme="dark"] .btn-check:not(:checked) + .btn-outline-primary {
    border-color: var(--umsa-border);
    color: var(--umsa-text-secondary);
}
[data-theme="dark"] hr { border-color: var(--umsa-border); }
</style>
@endsection