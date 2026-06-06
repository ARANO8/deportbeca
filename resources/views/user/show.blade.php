@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-user-circle"></i> Detalles del Usuario
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
       
        
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3 pb-2 border-bottom">
                    <i class="fas fa-id-card text-primary"></i> Información Personal
                </h4>
            </div>
        </div>
        
      
        
        <div class="row">
            <div class="col-md-4">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-user-tag"></i> Nombre
                    </label>
                    <p class="h6 text-dark">
                        {{$users->name}}
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-user-tag"></i> Apellido Paterno
                    </label>
                    <p class="h6 text-dark">
                        {{$users->apaterno}}
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-user-tag"></i> Apellido Materno
                    </label>
                    <p class="h6 text-dark">
                        {{$users->amaterno}}
                    </p>
                </div>
            </div>
        </div>
        
        
        
        <!-- Información de Contacto -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h4 class="mb-3 pb-2 border-bottom">
                    <i class="fas fa-address-card text-primary"></i> Información de Contacto
                </h4>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <p class="h6 text-dark">
                        <a href="mailto:{{$users->email}}" class="text-primary">
                            {{$users->email}}
                        </a>
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-phone"></i> Celular/Teléfono
                    </label>
                    <p class="h6 text-dark">
                        <a href="tel:{{$users->telefono}}" class="text-primary">
                            {{$users->telefono}}
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Información de Cuenta -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h4 class="mb-3 pb-2 border-bottom">
                    <i class="fas fa-cogs text-primary"></i> Información de Cuenta
                </h4>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-user-shield"></i> Rol
                    </label>
                    <p class="h6">
                        @if($users->rol)
                            <span class="badge badge-primary badge-lg p-2">
                                <i class="fas fa-user-shield"></i> {{ $users->rol->nombre }}
                            </span>
                        @else
                            <span class="badge badge-secondary badge-lg p-2">
                                <i class="fas fa-user"></i> Sin rol asignado
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-toggle-on"></i> Estado
                    </label>
                    <p class="h6">
                        @if($users->status == 'inactivo')
                            <span class="badge badge-danger badge-lg p-2">
                                <i class="fas fa-ban"></i> Inactivo
                            </span>
                        @else
                            <span class="badge badge-success badge-lg p-2">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Botones de Acción -->
     
    </div>
</div>

<style>
    .info-card {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        background: #eef2ff;
        transform: translateX(5px);
    }
    
    .badge-lg {
        font-size: 0.85rem;
        padding: 8px 15px;
    }
    
    .border-bottom {
        border-bottom: 2px solid #e9ecef !important;
    }
    
    .alert-info {
        border: none;
        border-radius: 12px;
    }
</style>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Animación de entrada
        $('.card').hide().fadeIn(400);
        
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
        
        // Efecto hover en las tarjetas de información
        $('.info-card').hover(
            function() {
                $(this).css('transform', 'translateX(5px)');
            },
            function() {
                $(this).css('transform', 'translateX(0)');
            }
        );
    });
    
</script>
@endsection