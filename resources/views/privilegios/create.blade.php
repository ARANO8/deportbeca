@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Rol</h3>
                </div>
                <form action="{{ route('privilegios.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre del Rol <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" required placeholder="Ej: admin, profe, user">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="status" class="form-control">
                                        <option value="active">Activo</option>
                                        <option value="inactive">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripcion</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripcion del rol"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Permisos del Rol</label>
                            <div class="row">
                                @php
                                    $permisos = App\Models\Permiso::orderBy('nombre')->get();
                                @endphp
                                @foreach($permisos as $permiso)
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}">
                                            {{ $permiso->nombre }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('privilegios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection