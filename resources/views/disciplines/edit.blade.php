@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-edit"></i> Editar Disciplina
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{ route('disciplinas.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i> Regresar
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{ route('disciplinas.update', $discipline->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="codigo">
                        <strong><i class="fas fa-barcode"></i> Código</strong>
                    </label>
                    <input type="text" name="codigo" id="codigo" class="form-control" 
                           value="{{ old('codigo', $discipline->codigo) }}" placeholder="Ej: DEP-001">
                    @error('codigo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-8">
                    <label for="nombre">
                        <strong><i class="fas fa-tag"></i> Nombre de la Disciplina</strong>
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control" 
                           value="{{ old('nombre', $discipline->nombre) }}" required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="descripcion">
                    <strong><i class="fas fa-align-left"></i> Descripción</strong>
                </label>
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion', $discipline->descripcion) }}</textarea>
                @error('descripcion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="parent_id">
                        <strong><i class="fas fa-layer-group"></i> Disciplina Padre (Opcional)</strong>
                    </label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">-- Ninguna (Disciplina principal) --</option>
                        @foreach($disciplinasPadre as $padre)
                            <option value="{{ $padre->id }}" {{ old('parent_id', $discipline->parent_id) == $padre->id ? 'selected' : '' }}>
                                {{ $padre->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="status">
                        <strong><i class="fas fa-toggle-on"></i> Estado</strong>
                    </label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="active" {{ old('status', $discipline->status) == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status', $discipline->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="mb-1"><strong><i class="fas fa-users"></i> Limites de integrantes por modalidad</strong></label>
                <small class="form-text text-muted mb-2">Deja vacios los campos de una modalidad si la disciplina no se ofrece de esa forma. El maximo puede quedar vacio si no hay tope oficial.</small>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-2">
                            <div class="card-body py-2">
                                <p class="mb-2"><i class="fas fa-users"></i> <strong>Grupal (equipo)</strong></p>
                                <div class="form-row">
                                    <div class="form-group col-6 mb-0">
                                        <label for="min_integrantes_grupal">Minimo</label>
                                        <input type="number" min="1" max="99" name="min_integrantes_grupal" id="min_integrantes_grupal" class="form-control" value="{{ old('min_integrantes_grupal', $discipline->min_integrantes_grupal) }}" placeholder="Ej: 6">
                                        @error('min_integrantes_grupal')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="form-group col-6 mb-0">
                                        <label for="max_integrantes_grupal">Maximo</label>
                                        <input type="number" min="1" max="99" name="max_integrantes_grupal" id="max_integrantes_grupal" class="form-control" value="{{ old('max_integrantes_grupal', $discipline->max_integrantes_grupal) }}" placeholder="Ej: 12">
                                        @error('max_integrantes_grupal')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-2">
                            <div class="card-body py-2">
                                <p class="mb-2"><i class="fas fa-user"></i> <strong>Individual (representantes)</strong></p>
                                <div class="form-row">
                                    <div class="form-group col-6 mb-0">
                                        <label for="min_integrantes_individual">Minimo</label>
                                        <input type="number" min="1" max="99" name="min_integrantes_individual" id="min_integrantes_individual" class="form-control" value="{{ old('min_integrantes_individual', $discipline->min_integrantes_individual) }}" placeholder="Ej: 1">
                                        @error('min_integrantes_individual')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="form-group col-6 mb-0">
                                        <label for="max_integrantes_individual">Maximo</label>
                                        <input type="number" min="1" max="99" name="max_integrantes_individual" id="max_integrantes_individual" class="form-control" value="{{ old('max_integrantes_individual', $discipline->max_integrantes_individual) }}" placeholder="Ej: 2">
                                        @error('max_integrantes_individual')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong><i class="fas fa-map-marker-alt"></i> Ubicacion en el mapa</strong></label>
                @include('partials.map-picker', ['id' => 'mapDisciplina', 'latField' => 'latitud', 'lngField' => 'longitud', 'lat' => $discipline->latitud, 'lng' => $discipline->longitud])
            </div>
            
            <hr>
            
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save"></i> Actualizar Disciplina
                </button>
                <a href="{{ route('disciplinas.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

