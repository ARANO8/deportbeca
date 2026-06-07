@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Nueva Disciplina
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
        <form action="{{ route('disciplinas.store') }}" method="POST">
            @csrf
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="codigo">
                        <strong><i class="fas fa-barcode"></i> Código</strong>
                    </label>
                    <input type="text" name="codigo" id="codigo" class="form-control" 
                           value="{{ old('codigo') }}" placeholder="Ej: DEP-001">
                    @error('codigo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-8">
                    <label for="nombre">
                        <strong><i class="fas fa-tag"></i> Nombre de la Disciplina</strong>
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control" 
                           value="{{ old('nombre') }}" placeholder="Ej: Fútbol, Voleibol, Ajedrez" required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="descripcion">
                    <strong><i class="fas fa-align-left"></i> Descripción</strong>
                </label>
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control" 
                          placeholder="Descripción de la disciplina...">{{ old('descripcion') }}</textarea>
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
                            <option value="{{ $padre->id }}" {{ old('parent_id') == $padre->id ? 'selected' : '' }}>
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
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="ubicacion_mapa">
                    <strong><i class="fas fa-map-marker-alt"></i> 🗺️ URL del Mapa (Google Maps Embed)</strong>
                </label>
                <input type="url" name="ubicacion_mapa" id="ubicacion_mapa" class="form-control" 
                       value="{{ old('ubicacion_mapa') }}" 
                       placeholder="https://www.google.com/maps/embed?pb=...">
                <small class="text-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Instrucciones:</strong> Ve a Google Maps → Busca la ubicación → Haz clic en "Compartir" → 
                    Selecciona "Insertar un mapa" → Copia la URL que está dentro de src="..."
                </small>
                @error('ubicacion_mapa')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            
            <!-- Vista previa del mapa -->
            <div id="mapPreview" class="mt-3" style="display: none;">
                <div class="alert alert-info">
                    <strong><i class="fas fa-map"></i> 🗺️ Vista previa del mapa:</strong>
                </div>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="mapIframe" class="embed-responsive-item" 
                            style="border:0; border-radius: 10px;" 
                            allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            
            <hr>
            
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save"></i> Guardar Disciplina
                </button>
                <a href="{{ route('disciplinas.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Vista previa del mapa
    $('#ubicacion_mapa').on('change keyup paste', function() {
        var mapUrl = $(this).val();
        
        if (mapUrl && mapUrl.trim() !== '') {
            if (mapUrl.includes('google.com/maps/embed')) {
                $('#mapIframe').attr('src', mapUrl);
                $('#mapPreview').fadeIn();
            } else {
                $('#mapPreview').fadeOut();
                toastr.warning('⚠️ Por favor, ingresa una URL válida de Google Maps Embed');
            }
        } else {
            $('#mapPreview').fadeOut();
            $('#mapIframe').attr('src', '');
        }
    });
    
    // Disparar el evento si ya hay un valor (por si hay error de validación)
    if ($('#ubicacion_mapa').val()) {
        $('#ubicacion_mapa').trigger('change');
    }
});
</script>
@endsection