@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-4">
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
                    <div class="col-8">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-search"></i> <strong>Buscar</strong>
                            </label>
                            <input class="form-control" type="search" placeholder="Buscar por nombre o ID..." id="search"
                                   value="{{(isset($_GET['search']))?$_GET['search']:''}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-newspaper text-primary"></i> Publicaciones
                </h3>
            </div>

            @puede('usuarios','crear')
            <div class="col text-right">
                <a href="{{url('/paginawebs/create')}}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nueva publicación
                </a>
            </div>
            @endpuede
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-items-center table-flush text-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col"><strong>Nº</strong></th>
                    <th scope="col"><strong>Imagen</strong></th>
                    <th scope="col"><strong>Nombre</strong></th>
                    <th scope="col"><strong>Fecha</strong></th>
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
                @foreach ($pagina as $paginas)
                <tr>
                    <td class="font-weight-bold">{{$valor++}}</td>
                    <td>
                        <img src="{{asset('imagen/'.$paginas->imagen)}}"
                             style="max-height: 80px; width: auto; cursor: pointer; border-radius: 10px;"
                             class="img-thumbnail img-hover"
                             onclick="openImageModal('{{ asset('imagen/'.$paginas->imagen) }}')"
                             title="Haz clic para ampliar">
                    </td>
                    <td class="font-weight-bold">{{$paginas->nombre}}</td>
                    <td class="font-weight-bold">
                            @if($paginas->created_at)
                                {{ $paginas->created_at->format('d/m/Y') }}
                            @else
                                N/A
                            @endif

                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            @puede('usuarios','ver')
                            <a href="{{url('/paginawebs/'.Crypt::encrypt($paginas->id))}}"
                               class="btn btn-sm btn-info" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endpuede
                            @puede('usuarios','editar')
                            <a href="{{url('/paginawebs/'.Crypt::encrypt($paginas->id).'/edit')}}"
                               class="btn btn-sm btn-primary" title="Editar publicación">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endpuede
                            @puede('usuarios','eliminar')
                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar publicación"
                                    onclick="confirmDelete({{$paginas->id}}, '{{ addslashes($paginas->nombre) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{$paginas->id}}" action="{{url('/paginawebs/'.$paginas->id)}}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endpuede
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($pagina->isEmpty())
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-newspaper fa-3x mb-3 d-block"></i>
                        No hay publicaciones registradas
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card-body pagination justify-content-end">
        @if($pagina->total() > 10)
            {{ $pagina->appends(request()->query())->links() }}
        @endif
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
                <img id="modalImage" src="" alt="Imagen de publicación" style="max-width: 100%; max-height: 70vh; border-radius: 10px;">
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
    .img-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .img-hover:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .btn-group .btn {
        margin: 0 2px;
    }
</style>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Animación de entrada
        $('.card').hide().fadeIn(400);

        // Filtro de límite
        $('#limit').on('change', function(){
            window.location.href = "{{ route('paginawebs.index') }}?limit=" + $(this).val() + '&search=' + $('#search').val();
        });

        // Búsqueda con Enter
        $('#search').on('keyup', function(e){
            if(e.keyCode == 13){
                window.location.href = "{{ route('paginawebs.index') }}?limit=" + $('#limit').val() + '&search=' + $(this).val();
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
    });

    // Función para abrir el modal con la imagen
    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('downloadImage').href = imageUrl;
        $('#imageModal').modal('show');
    }

    // Función para confirmar eliminación
    function confirmDelete(id, nombre) {
        Swal.fire({
            title: '¿Eliminar publicación?',
            html: '¿Estás seguro de eliminar la publicación <strong>"' + nombre + '"</strong>?',
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

<!-- SweetAlert2 para confirmaciones elegantes -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
