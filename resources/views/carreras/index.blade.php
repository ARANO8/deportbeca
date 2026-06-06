@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-list"></i> <strong>Listar</strong>
                            </label>
                            <select class="custom-select" id="limit" name="limit">
                                @foreach ([10,20,50,100] as $limit)
                                <option value="{{$limit}}" @if(isset($_GET['limit']))
                                    {{($_GET['limit']==$limit)?'selected': ''}} @endif>{{$limit}}</option>
                                @endforeach
                            </select>
                            <?php
                            if(isset($_GET['page'])){
                                $pag=$_GET['page'];
                            }else{
                                $pag=1;
                            }
                            if(isset($_GET['limit'])){
                                $limit=$_GET['limit'];
                            }else{
                                $limit=10;
                            }
                            $comienzo=$limit*($pag-1);
                            ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-university"></i> <strong>Filtrar por Facultad</strong>
                            </label>
                            <select class="custom-select" id="facultad_filter" name="facultad_id">
                                <option value="">Todas las facultades</option>
                                @foreach($facultades as $facultad)
                                <option value="{{$facultad->id}}" @if(isset($_GET['facultad_id']) && $_GET['facultad_id'] == $facultad->id) selected @endif>
                                    {{$facultad->nombre}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-5">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-search"></i> <strong>Buscar</strong>
                            </label>
                            <input class="form-control" type="search" placeholder="Buscar por código o nombre..." id="search" 
                                   value="{{(isset($_GET['search']))?$_GET['search']:''}}">
                        </div>
                    </div> -->
                </div>
            </div>
            
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-graduation-cap text-primary"></i> Carreras
                </h3>
            </div>
            
            <div class="col text-right">
                <a href="{{ route('carreras.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nueva Carrera
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table align-items-center table-flush text-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col"><strong>Nº</strong></th>
                    <th scope="col"><strong>Código</strong></th>
                    <th scope="col"><strong>Nombre</strong></th>
                    <th scope="col"><strong>Facultad</strong></th>
                    <th scope="col"><strong>Estado</strong></th>
                    <th scope="col"><strong>Fecha Creación</strong></th>
                    <th scope="col"><strong>Opciones</strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $valor=1;
                if($pag!=1){
                    $valor=$comienzo+1;
                }
                ?>
                @foreach ($carreras as $carrera)
                <tr>
                    <td class="font-weight-bold">{{$valor++}}</td>
                    <td><strong>{{$carrera->codigo}}</strong></td>
                    <td>{{$carrera->nombre}}</td>
                    <td>{{$carrera->facultad ? $carrera->facultad->nombre : 'N/A'}}</td>
                    <td>
                        @if($carrera->status == 'inactive')
                            <span class="badge badge-danger"><strong>Inactivo</strong></span>
                        @else
                            <span class="badge badge-success"><strong>Activo</strong></span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i> 
                            {{ $carrera->created_at ? $carrera->created_at->format('d/m/Y') : 'N/A' }}
                        </small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('carreras.show', $carrera->id) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('carreras.edit', $carrera->id) }}" class="btn btn-sm btn-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if($carrera->status == 'inactive')
                                <form action="{{ route('carreras.activo', $carrera->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Activar esta carrera?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" title="Activar"><i class="fas fa-check"></i></button>
                                </form>
                            @else
                                <form action="{{ route('carreras.inactivo', $carrera->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Inactivar esta carrera?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-warning" title="Inactivar"><i class="fas fa-ban"></i></button>
                                </form>
                            @endif
                            
                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar"
                                    onclick="confirmDelete({{$carrera->id}}, '{{ addslashes($carrera->nombre) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{$carrera->id}}" action="{{ route('carreras.destroy', $carrera->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($carreras->isEmpty())
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-graduation-cap fa-3x mb-3 d-block"></i>
                        No hay carreras registradas
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="card-body pagination justify-content-end">
        @if($carreras->total() > 10)
            {{ $carreras->appends(request()->query())->links() }}
        @endif
    </div>
</div>

<style>
    .btn-group .btn {
        margin: 0 2px;
    }
    .custom-select {
        cursor: pointer;
    }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Animación de entrada
        $('.card').hide().fadeIn(400);
        
        // Filtro de límite
        $('#limit').on('change', function(){
            window.location.href = "{{ route('carreras.index') }}?limit=" + $(this).val() + '&search=' + $('#search').val() + '&facultad_id=' + $('#facultad_filter').val();
        });
        
        // Búsqueda con Enter
        $('#search').on('keyup', function(e){
            if(e.keyCode == 13){
                window.location.href = "{{ route('carreras.index') }}?limit=" + $('#limit').val() + '&search=' + $(this).val() + '&facultad_id=' + $('#facultad_filter').val();
            }
        });
        
        // Filtro por facultad
        $('#facultad_filter').on('change', function(){
            window.location.href = "{{ route('carreras.index') }}?limit=" + $('#limit').val() + '&search=' + $('#search').val() + '&facultad_id=' + $(this).val();
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
        
        // Compatibilidad con notificaciones antiguas
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    });
    
    // Función para confirmar eliminación con SweetAlert
    function confirmDelete(id, nombre) {
        Swal.fire({
            title: '¿Eliminar carrera?',
            html: `¿Estás seguro de eliminar la carrera <strong>"${nombre}"</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection