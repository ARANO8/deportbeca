<?php
use Illuminate\Support\Str;

function generateRandomPassword($length = 8) {
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*()';

    $password = $letters[rand(0, strlen($letters) - 1)];
    $password .= $numbers[rand(0, strlen($numbers) - 1)];
    $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

    $allChars = $letters . $numbers . $specialChars;
    for ($i = 3; $i < $length; $i++) {
        $password .= $allChars[rand(0, strlen($allChars) - 1)];
    }

    return str_shuffle($password);
}

$generatedPassword = generateRandomPassword(8);
?>

@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-user-plus"></i> Nuevo usuario
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
        
        <form action="{{url('/users')}}" method="POST" id="createUserForm">
            @csrf
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">
                        <strong><i class="fas fa-envelope"></i> Correo Electrónico</strong>
                    </label>
                    <input type="email" name="email" class="form-control text-indigo" 
                           value="{{old('email')}}" placeholder="ejemplo@correo.com" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="password">
                        <strong><i class="fas fa-key"></i> Contraseña</strong>
                    </label>
                    <div class="input-group">
                        <input type="text" name="password" id="password" class="form-control" 
                               value="{{old('password', $generatedPassword)}}" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="generatePasswordBtn" title="Generar nueva contraseña">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-warning">
                        <i class="fas fa-info-circle"></i> La contraseña debe tener al menos 8 caracteres, incluyendo un carácter especial
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
                           value="{{old('name')}}" placeholder="Nombre" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="apaterno">
                        <strong><i class="fas fa-user-tag"></i> Apellido Paterno</strong>
                    </label>
                    <input type="text" name="apaterno" class="form-control text-indigo" 
                           value="{{old('apaterno')}}" placeholder="Apellido paterno" required>
                    @error('apaterno')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="amaterno">
                        <strong><i class="fas fa-user-tag"></i> Apellido Materno</strong>
                    </label>
                    <input type="text" name="amaterno" class="form-control text-indigo" 
                           value="{{old('amaterno')}}" placeholder="Apellido materno" required>
                    @error('amaterno')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                
                
                <div class="form-group col-md-4">
                    <label for="telefono">
                        <strong><i class="fas fa-phone"></i> Celular/Teléfono</strong>
                    </label>
                    <input type="text" name="telefono" class="form-control text-indigo" 
                           value="{{old('telefono')}}" placeholder="Número de contacto" required>
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
                            <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="sendEmailCheckbox" checked>
                    <label class="custom-control-label" for="sendEmailCheckbox">
                        <i class="fas fa-envelope"></i> Enviar credenciales al correo del usuario
                    </label>
                </div>
            </div>
            
            <hr>
            
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save"></i> Crear usuario
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
        
        // Generar nueva contraseña aleatoria
        $('#generatePasswordBtn').on('click', function() {
            $.ajax({
                url: "{{ url('/generate-password') }}",
                method: 'GET',
                success: function(response) {
                    $('#password').val(response.password);
                    toastr.info('🔑 Nueva contraseña generada automáticamente');
                },
                error: function() {
                    // Fallback: recargar la página para obtener nueva contraseña
                    location.reload();
                }
            });
        });
        
        $('#password').on('keyup', function() {
            var password = $(this).val();
            var hasSpecialChar = /[^\w]/.test(password);
            var hasMinLength = password.length >= 8;
            
            if (hasMinLength && hasSpecialChar) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
        $('#createUserForm').on('submit', function(e) {
            var password = $('#password').val();
            var hasSpecialChar = /[^\w]/.test(password);
            var hasMinLength = password.length >= 8;
            
            if (!hasMinLength || !hasSpecialChar) {
                e.preventDefault();
                toastr.error('⚠️ La contraseña debe tener al menos 8 caracteres y un carácter especial');
                return false;
            }
            
            // Mostrar loading
            var $btn = $(this).find('button[type="submit"]');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Creando usuario...').prop('disabled', true);
            
           
            setTimeout(function() {
                $btn.html('<i class="fas fa-save"></i> Crear usuario').prop('disabled', false);
            }, 3000);
        });
        
        // Tooltips
        $('[title]').tooltip();
    });
</script>
@endsection