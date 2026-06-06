<?php use Illuminate\Support\Str; ?>
@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-user-edit"></i> Editar usuario
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{url('/users')}}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i>
                    Regresar
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{url('/users/'.$user->id)}}" method="POST" id="editUserForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">
                        <strong><i class="fas fa-envelope"></i> Correo Electrónico</strong>
                    </label>
                    <input type="email" name="email" class="form-control text-indigo" 
                           value="{{old('email', $user->email)}}" placeholder="ejemplo@correo.com" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="password">
                        <strong><i class="fas fa-key"></i> Contraseña</strong>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control text-indigo" 
                               placeholder="Nueva contraseña (opcional)">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="showPasswordBtn" title="Mostrar contraseña">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-warning">
                        <i class="fas fa-info-circle"></i> Solo llena el campo si deseas cambiar la contraseña. Debe tener al menos 8 caracteres incluyendo un carácter especial.
                    </small>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="name">
                        <strong><i class="fas fa-user"></i> Nombre</strong>
                    </label>
                    <input type="text" name="name" class="form-control text-indigo" 
                           value="{{old('name', $user->name)}}" placeholder="Nombre" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="apaterno">
                        <strong><i class="fas fa-user-tag"></i> Apellido Paterno</strong>
                    </label>
                    <input type="text" name="apaterno" class="form-control text-indigo" 
                           value="{{old('apaterno', $user->apaterno)}}" placeholder="Apellido paterno" required>
                    @error('apaterno')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="amaterno">
                        <strong><i class="fas fa-user-tag"></i> Apellido Materno</strong>
                    </label>
                    <input type="text" name="amaterno" class="form-control text-indigo" 
                           value="{{old('amaterno', $user->amaterno)}}" placeholder="Apellido materno" required>
                    @error('amaterno')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="status">
                        <strong><i class="fas fa-toggle-on"></i> Estado</strong>
                    </label>
                    <select class="form-control text-indigo" id="status" name="status">
                        <option value="activo" {{($user->status === 'activo') ? 'selected' : ''}}>
                            <i class="fas fa-check-circle text-success"></i> Activo
                        </option>
                        <option value="inactivo" {{($user->status === 'inactivo') ? 'selected' : ''}}>
                            <i class="fas fa-ban text-danger"></i> Inactivo
                        </option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="telefono">
                        <strong><i class="fas fa-phone"></i> Celular/Teléfono</strong>
                    </label>
                    <input type="text" name="telefono" class="form-control text-indigo" 
                           value="{{old('telefono', $user->telefono)}}" placeholder="Número de contacto" required>
                    @error('telefono')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="rol_id">
                        <strong><i class="fas fa-user-shield"></i> Rol del usuario</strong>
                    </label>
                    <select class="form-control text-indigo" id="rol_id" name="rol_id" required>
                        <option value="">Seleccione un rol</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ (old('rol_id', $user->rol_id) == $rol->id) ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
           
            
            <hr>
            
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
                <a href="{{url('/users')}}" class="btn btn-sm btn-secondary">
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
        
        // Mostrar/Ocultar contraseña
        let passwordVisible = false;
        $('#showPasswordBtn').on('click', function() {
            const passwordInput = $('#password');
            if (passwordVisible) {
                passwordInput.attr('type', 'password');
                $(this).html('<i class="fas fa-eye"></i>');
                $(this).attr('title', 'Mostrar contraseña');
                passwordVisible = false;
            } else {
                passwordInput.attr('type', 'text');
                $(this).html('<i class="fas fa-eye-slash"></i>');
                $(this).attr('title', 'Ocultar contraseña');
                passwordVisible = true;
            }
        });
        
        // Validación en tiempo real para la contraseña (solo si se está cambiando)
        $('#password').on('keyup', function() {
            var password = $(this).val();
            
            if (password.length > 0) {
                var hasSpecialChar = /[^\w]/.test(password);
                var hasMinLength = password.length >= 8;
                
                if (hasMinLength && hasSpecialChar) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });
        
        // Confirmación antes de enviar
        $('#editUserForm').on('submit', function(e) {
            var password = $('#password').val();
            
            if (password.length > 0) {
                var hasSpecialChar = /[^\w]/.test(password);
                var hasMinLength = password.length >= 8;
                
                if (!hasMinLength || !hasSpecialChar) {
                    e.preventDefault();
                    toastr.error('⚠️ La contraseña debe tener al menos 8 caracteres y un carácter especial');
                    return false;
                }
            }
            
            // Mostrar loading
            var $btn = $(this).find('button[type="submit"]');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando cambios...').prop('disabled', true);
            
            // El formulario se enviará normalmente
            setTimeout(function() {
                $btn.html('<i class="fas fa-save"></i> Guardar cambios').prop('disabled', false);
            }, 3000);
        });
        
        // Tooltips
        $('[title]').tooltip();
        
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
        
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });
</script>
@endsection