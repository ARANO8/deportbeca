<?php use Illuminate\Support\Str; ?>
@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-plus-circle text-primary"></i> Nueva Publicación
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{ route('paginawebs.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i>
                    Regresar
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form id="formCrear" action="{{ url('/paginawebs') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="send_email" id="send_email" value="0">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombre">
                        <strong><i class="fas fa-tag"></i> Tipo de comunicado</strong>
                    </label>
                    <input type="text" name="nombre" class="form-control" 
                           value="{{old('nombre')}}" placeholder="Ej: Reunión informativa, Evento deportivo..." required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="imagen">
                        <strong><i class="fas fa-image"></i> Elija la imagen del comunicado</strong>
                    </label>
                    <div class="custom-file">
                        <input type="file" id="imagen" name="imagen" class="custom-file-input" 
                               accept="image/png, image/jpeg, image/jpg, image/gif" required>
                        <label class="custom-file-label" for="imagen">Seleccionar archivo</label>
                    </div>
                    <small class="text-muted">Formatos: PNG, JPEG, JPG, GIF. Máximo 2MB</small>
                    @error('imagen')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="descripcion">
                        <strong><i class="fas fa-align-left"></i> Descripción del comunicado</strong>
                    </label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="10" 
                              placeholder="Ingrese la descripción detallada del comunicado..." required>{{old('descripcion')}}</textarea>
                    @error('descripcion')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label>
                        <strong><i class="fas fa-eye"></i> Vista previa de la imagen</strong>
                    </label>
                    <div class="text-center border rounded p-3 bg-light" style="min-height: 320px;">
                        <img id="imagenSeleccionada" class="img-fluid rounded" 
                             style="max-height: 250px; width: auto; display: none;">
                        <div id="placeholderImage" class="text-muted">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-2 d-block"></i>
                            <p>Selecciona una imagen para previsualizar</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">
                    <i class="fas fa-save"></i> Crear publicación
                </button>
                <a href="{{ route('paginawebs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Confirmación Mejorado -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-question-circle"></i> Confirmar Publicación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-envelope"></i> 
                    <strong>Se enviará notificación a los siguientes capitanes de equipo:</strong>
                </div>
                <div class="resident-list" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group">
                        @forelse($destinatarios as $destinatario)
                            <li class="list-group-item">
                                <i class="fas fa-user-circle text-primary"></i>
                                <strong>{{ $destinatario->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-envelope"></i> {{ $destinatario->email }}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">
                                <i class="fas fa-info-circle"></i> No hay destinatarios registrados
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="guardarSinEnviar()">
                    <i class="fas fa-save"></i> Solo Guardar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarYEnviar()">
                    <i class="fas fa-paper-plane"></i> Guardar y Enviar a {{ count($destinatarios) }} capitanes de equipo
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-file-label::after {
        content: "📁 Buscar";
    }
    .img-fluid {
        transition: transform 0.3s ease;
        max-height: 250px;
        width: auto;
        margin: 0 auto;
        display: block;
    }
    .img-fluid:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .list-group-item {
        transition: background 0.2s ease;
    }
    .list-group-item:hover {
        background: #f8f9fa;
    }
    .resident-list::-webkit-scrollbar {
        width: 6px;
    }
    .resident-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .resident-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
</style>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Animación de entrada
        $('.card').hide().fadeIn(400);
        
        // Vista previa de imagen
        $('#imagen').change(function(e) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagenSeleccionada').attr('src', e.target.result).show();
                $('#placeholderImage').hide();
            }
            reader.readAsDataURL(this.files[0]);
            
            // Actualizar el nombre del archivo en el input
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Validación antes de mostrar modal
        $('button[data-target="#confirmModal"]').on('click', function(e) {
            var nombre = $('#nombre').val();
            var descripcion = $('#descripcion').val();
            var imagen = $('#imagen').val();
            
            if (nombre.trim() == '') {
                e.preventDefault();
                toastr.error('⚠️ El tipo de comunicado es obligatorio');
                $('#nombre').focus();
                return false;
            }
            
            if (descripcion.trim() == '') {
                e.preventDefault();
                toastr.error('⚠️ La descripción es obligatoria');
                $('#descripcion').focus();
                return false;
            }
            
            if (imagen == '') {
                e.preventDefault();
                toastr.error('⚠️ Debe seleccionar una imagen');
                return false;
            }
        });
        
        // Mostrar notificaciones Toastr
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
        
        // Compatibilidad con notificaciones antiguas
        @if(session('notification'))
            toastr.success("{{ session('notification') }}");
        @endif
        
        @if(session('notifications'))
            toastr.error("{{ session('notifications') }}");
        @endif
        
        // Mostrar errores de validación
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });

    function guardarSinEnviar() {
        $('#send_email').val('0');
        $('#formCrear').submit();
    }

    function guardarYEnviar() {
        $('#send_email').val('1');
        $('#formCrear').submit();
    }
</script>
@endsection