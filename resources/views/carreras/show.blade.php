@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-info-circle text-primary"></i> Detalles de la Carrera
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
        <!-- Información de la Carrera -->
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3 pb-2 border-bottom">
                    <i class="fas fa-graduation-cap text-primary"></i> Información de la Carrera
                </h4>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-barcode"></i> Código
                    </label>
                    <p class="h6 text-dark font-weight-semibold">
                        <span class="badge badge-light p-2">{{ $carrera->codigo }}</span>
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-graduation-cap"></i> Nombre
                    </label>
                    <p class="h6 text-dark">
                        {{ $carrera->nombre }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-university"></i> Facultad
                    </label>
                    <p class="h6 text-dark">
                        {{ $carrera->facultad ? $carrera->facultad->nombre : 'N/A' }}
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-toggle-on"></i> Estado
                    </label>
                    <p class="h6">
                        @if($carrera->status == 'inactive')
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
        
        <!-- Información de Fechas -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h4 class="mb-3 pb-2 border-bottom">
                    <i class="fas fa-calendar-alt text-primary"></i> Información de Registro
                </h4>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-calendar-plus"></i> Fecha de Creación
                    </label>
                    <p class="h6 text-dark">
                        {{ $carrera->created_at ? $carrera->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <label class="text-muted mb-1">
                        <i class="fas fa-calendar-edit"></i> Última Actualización
                    </label>
                    <p class="h6 text-dark">
                        {{ $carrera->updated_at ? $carrera->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
        
       
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
</style>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
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
    });
</script>
@endsection