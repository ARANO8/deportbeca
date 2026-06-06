@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Disciplina</h3>
                </div>
                <form action="{{ route('disciplines.update', $discipline) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Código <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                   value="{{ old('codigo', $discipline->codigo) }}" maxlength="20" required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $discipline->nombre) }}" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="3">{{ old('descripcion', $discipline->descripcion) }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Disciplina Padre (opcional)</label>
                            <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">-- Ninguna (Disciplina Principal) --</option>
                                @foreach($mainDisciplines as $main)
                                    <option value="{{ $main->id }}" 
                                        {{ old('parent_id', $discipline->parent_id) == $main->id ? 'selected' : '' }}>
                                        [{{ $main->codigo }}] {{ $main->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if($discipline->subDisciplines->count() > 0)
                                <small class="form-text text-warning">⚠️ Esta disciplina tiene subdisciplinas, no puede convertirse en subdisciplina.</small>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $discipline->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $discipline->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection