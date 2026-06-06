@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nueva Disciplina</h3>
                </div>
                <form action="{{ route('disciplines.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Código <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                   value="{{ old('codigo') }}" maxlength="20" required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Ej: ATL, NAT, FUT (máx 20 caracteres)</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Disciplina Principal (opcional)</label>
                            <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">-- Ninguna (Disciplina Principal) --</option>
                                @foreach($mainDisciplines as $main)
                                    <option value="{{ $main->id }}" {{ old('parent_id') == $main->id ? 'selected' : '' }}>
                                        [{{ $main->codigo }}] {{ $main->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Si selecciona una disciplina padre, esto será una subdisciplina.</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection