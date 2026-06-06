@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Rol</h3>
                </div>
                <form action="{{ route('roles.update', $rol->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nombre del Rol <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $rol->nombre) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Descripcion</label>
                            <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $rol->descripcion) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ $rol->status == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ $rol->status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection