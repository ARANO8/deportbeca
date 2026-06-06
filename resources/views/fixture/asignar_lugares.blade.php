{{-- resources/views/fixture/asignar_lugares.blade.php --}}
@extends('layouts.panel')

@section('title', 'Asignar Lugares y Horarios')

@section('content')
<div class="row">
    <div class="col">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary">
                <h2 class="text-white mb-0">
                    <i class="fas fa-map-marker-alt mr-2"></i> Asignar Lugares y Horarios
                </h2>
                <p class="text-white-50 mb-0">Configure dónde y cuándo se jugarán los partidos</p>
            </div>
            
            <div class="card-body">
                <form action="{{ route('fixture.guardar_asignacion', $eventoConfiguracionId) }}" method="POST">
                    @csrf
                    
                    @foreach($series as $index => $serie)
                        <div class="card mb-3 border">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">
                                        <span class="badge badge-primary">{{ $serie['nombre'] }}</span>
                                    </h4>
                                    <span class="text-muted">
                                        <i class="fas fa-users mr-1"></i> {{ $serie['cantidad'] }} participantes
                                        ({{ $serie['individuales'] }} individuales, {{ $serie['grupales'] }} grupales)
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="series[{{ $index }}][id]" value="{{ $serie['id'] }}">
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-location-dot text-danger mr-1"></i> Lugar</label>
                                            <select name="series[{{ $index }}][lugar_id]" class="form-control">
                                                <option value="">Seleccionar lugar...</option>
                                                @foreach($lugares as $lugar)
                                                    <option value="{{ $lugar->id }}">{{ $lugar->nombre }} - {{ $lugar->direccion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-calendar-day mr-1"></i> Fecha inicio</label>
                                            <input type="date" 
                                                   name="series[{{ $index }}][fecha_inicio]" 
                                                   class="form-control"
                                                   min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><i class="fas fa-clock mr-1"></i> Hora inicio</label>
                                            <input type="time" 
                                                   name="series[{{ $index }}][hora_inicio]" 
                                                   class="form-control"
                                                   value="09:00">
                                        </div>
                                    </div>
                                </div>
                                
                                <details class="mt-2">
                                    <summary class="text-muted" style="cursor: pointer;">
                                        <i class="fas fa-eye mr-1"></i> Ver participantes en esta serie
                                    </summary>
                                    <div class="mt-2 pl-3">
                                        @foreach($serie['participantes'] as $p)
                                            <div class="small text-muted">
                                                <i class="fas fa-{{ $p['tipo_inscripcion'] === 'individual' ? 'user' : 'users' }} mr-1"></i>
                                                {{ $p['tipo_inscripcion'] === 'individual' ? $p['representante_nombre'] : $p['nombre_equipo'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="mt-4 text-right">
                        <a href="{{ route('fixture.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle mr-1"></i> Generar Fixture Completo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection