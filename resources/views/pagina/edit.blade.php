@extends('layouts.panel')

@if($role=='admin' || $role=='profe')
@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-edit text-primary"></i> Editar Publicación
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{url('/paginawebs')}}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i>
                    Regresar
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{url('/paginawebs/'.$pagina->id)}}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')
            
            @if(auth()->user()->esSuperAdmin())
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombre">
                        <strong><i class="fas fa-bullhorn"></i> Titulo</strong>
                    </label>
                    <input type="text" name="nombre" class="form-control" 
                           value="{{old('nombre', $pagina->nombre)}}" placeholder="Ingrese el título del comunicado" required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="imagen">
                        <strong><i class="fas fa-image"></i> Elija la imagen del publicacion</strong>
                    </label>
                    <div class="custom-file">
                        <input type="file" name="imagen" id="imagen" class="custom-file-input" 
                               accept="image/png, image/jpeg, image/jpg, image/gif">
                        <label class="custom-file-label" for="imagen">Seleccionar archivo</label>
                    </div>
                    <small class="text-muted">Formatos permitidos: PNG, JPEG, JPG, GIF. Máximo 2MB</small>
                    @error('imagen')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="descripcion">
                        <strong><i class="fas fa-align-left"></i> Descripción de la publicacion</strong>
                    </label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="10" 
                              placeholder="Ingrese la descripción del comunicado..." required>{{old('descripcion', $pagina->descripcion)}}</textarea>
                    @error('descripcion')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label>
                        <strong><i class="fas fa-eye"></i> Vista previa de la imagen</strong>
                    </label>
                    <div class="text-center border rounded p-3 bg-light">
                        <img src="{{ asset('imagen/' . $pagina->imagen) }}" 
                             id="imagenSeleccionada" 
                             class="img-fluid rounded" 
                             style="max-height: 250px; width: auto; cursor: pointer;"
                             onclick="openImageModal('{{ asset('imagen/' . $pagina->imagen) }}')"
                             alt="Vista previa de la imagen">
                    </div>
                    <small class="text-muted">Haz clic en la imagen para ampliarla</small>
                </div>
            </div>
            
            
            @else
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="imagen">
                        <strong><i class="fas fa-image"></i> Elija la imagen del comunicado</strong>
                    </label>
                    <div class="custom-file">
                        <input type="file" name="imagen" id="imagen" class="custom-file-input" 
                               accept="image/png, image/jpeg, image/jpg, image/gif">
                        <label class="custom-file-label" for="imagen">Seleccionar archivo</label>
                    </div>
                    <small class="text-muted">Formatos permitidos: PNG, JPEG, JPG, GIF. Máximo 2MB</small>
                    @error('imagen')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>
                        <strong><i class="fas fa-eye"></i> Vista previa de la imagen</strong>
                    </label>
                    <div class="text-center border rounded p-3 bg-light">
                        <img src="{{ asset('imagen/' . $pagina->imagen) }}" 
                             id="imagenSeleccionada" 
                             class="img-fluid rounded" 
                             style="max-height: 250px; width: auto; cursor: pointer;"
                             onclick="openImageModal('{{ asset('imagen/' . $pagina->imagen) }}')"
                             alt="Vista previa de la imagen">
                    </div>
                    <small class="text-muted">Haz clic en la imagen para ampliarla</small>
                </div>
            </div>
            @endif
            
            <hr>
            
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
                <a href="{{url('/paginawebs')}}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal para ver imagen en grande -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-image text-primary"></i> Imagen de la Publicación
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Imagen" style="max-width: 100%; max-height: 70vh; border-radius: 10px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <a id="downloadImage" href="#" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Descargar
                </a>
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
    }
    .img-fluid:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

@endsection

@else

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-lock text-danger"></i> Acceso Denegado
                </h3>
            </div>
        </div>
    </div>
    <div class="card-body text-center py-5">
        <i class="fas fa-ban fa-4x text-danger mb-3 d-block"></i>
        <h1 class="text-danger">ACCESO NO AUTORIZADO</h1>
        <p class="text-muted mb-4">Usted no tiene permisos para acceder a esta sección.</p>
        <a href="{{url('/home')}}" class="btn btn-success btn-lg">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
    </div>
</div>

@endsection

@endif

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
                $('#imagenSeleccionada').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
            
            // Actualizar el nombre del archivo en el input
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Validación antes de enviar
        $('#editForm').on('submit', function(e) {
            var nombre = $('#nombre').val();
            var descripcion = $('#descripcion').val();
            
            if (nombre.trim() == '') {
                e.preventDefault();
                toastr.error('⚠️ El título del comunicado es obligatorio');
                $('#nombre').focus();
                return false;
            }
            
            if (descripcion.trim() == '') {
                e.preventDefault();
                toastr.error('⚠️ La descripción es obligatoria');
                $('#descripcion').focus();
                return false;
            }
            
            // Mostrar loading
            var $btn = $('#submitBtn');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
            
            setTimeout(function() {
                $btn.html('<i class="fas fa-save"></i> Guardar cambios').prop('disabled', false);
            }, 3000);
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
    
    // Función para abrir el modal con la imagen
    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('downloadImage').href = imageUrl;
        $('#imageModal').modal('show');
    }
</script>
@endsection