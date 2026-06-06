@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-plus-circle text-primary"></i> Crear Nueva Carrera
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{ route('carreras.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i>
                    Regresar
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{ route('carreras.store') }}" method="POST" id="createForm">
            @csrf
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="codigo">
                        <strong><i class="fas fa-barcode"></i> Código</strong>
                    </label>
                    <input type="text" name="codigo" id="codigo" 
                           class="form-control @error('codigo') is-invalid @enderror"
                           value="{{ old('codigo') }}" 
                           placeholder="Ej: FCPN-INF" 
                           maxlength="20" required>
                    @error('codigo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="nombre">
                        <strong><i class="fas fa-graduation-cap"></i> Nombre de la Carrera</strong>
                    </label>
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" 
                           placeholder="Ej: Ingeniería Informática" 
                           maxlength="150" required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="facultad_id">
                        <strong><i class="fas fa-university"></i> Facultad</strong>
                    </label>
                    <select name="facultad_id" id="facultad_id" 
                            class="form-control @error('facultad_id') is-invalid @enderror" required>
                        <option value="">Seleccionar facultad</option>
                        @foreach($facultades as $facultad)
                            <option value="{{ $facultad->id }}" {{ old('facultad_id') == $facultad->id ? 'selected' : '' }}>
                                {{ $facultad->codigo }} - {{ $facultad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('facultad_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="status">
                        <strong><i class="fas fa-toggle-on"></i> Estado</strong>
                    </label>
                    <select name="status" id="status" 
                            class="form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <hr>
            
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Guardar Carrera
                </button>
                <a href="{{ route('carreras.index') }}" class="btn btn-sm btn-secondary">
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
        // Animación de entrada
        $('.card').hide().fadeIn(400);
        
        // Validación antes de enviar
        $('#createForm').on('submit', function(e) {
            var codigo = $('#codigo').val();
            var nombre = $('#nombre').val();
            var facultad_id = $('#facultad_id').val();
            
            if (codigo.trim() == '') {
                e.preventDefault();
                toastr.error('⚠️ El código de la carrera es obligatorio');
                $('#codigo').focus();
                return false;
            }
            
            if (nombre.trim() == '') {
                e.preventDefault();
                toastr.error('⚠️ El nombre de la carrera es obligatorio');
                $('#nombre').focus();
                return false;
            }
            
            if (facultad_id == '') {
                e.preventDefault();
                toastr.error('⚠️ Debe seleccionar una facultad');
                $('#facultad_id').focus();
                return false;
            }
            
            // Mostrar loading
            var $btn = $('#submitBtn');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
            
            setTimeout(function() {
                $btn.html('<i class="fas fa-save"></i> Guardar Carrera').prop('disabled', false);
            }, 3000);
        });
        
        // Mostrar notificaciones Toastr automáticamente
        @if(session('toastr_success'))
            toastr.success("{{ session('toastr_success') }}");
        @endif
        
        @if(session('toastr_error'))
            toastr.error("{{ session('toastr_error') }}");
        @endif
        
        @if(session('toastr_warning'))
            toastr.warning("{{ session('toastr_warning') }}");
        @endif
        
        @if(session('toastr_info'))
            toastr.info("{{ session('toastr_info') }}");
        @endif
        
        // Mostrar errores de validación
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });
</script>
@endsection