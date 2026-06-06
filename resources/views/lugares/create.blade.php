@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Lugar</h3>
                </div>
                <form action="{{ route('admin.lugares.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Codigo <span class="text-danger">*</span></label>
                                    <input type="text" name="codigo" class="form-control" required maxlength="20" placeholder="Ej: COL-001">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" required placeholder="Ej: Coliseo Universitario">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripcion</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripcion del lugar"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Direccion <span class="text-danger">*</span></label>
                            <input type="text" name="direccion" class="form-control" required placeholder="Ej: Av. Villazon, La Paz">
                        </div>

                        <div class="form-group">
                            <label>Codigo embed de Google Maps</label>
                            <textarea name="embed_mapa" class="form-control" rows="4" placeholder="iframe src='https://www.google.com/maps/embed?pb=...'"></textarea>
                            <small class="text-muted">Pega aqui el codigo iframe de Google Maps</small>
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="status" class="form-control">
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection