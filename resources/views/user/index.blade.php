@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <a class="navbar-brand"><strong>Listar</strong></a>
                            <select class="custom-select text-indigo" id="limit" name="limit">
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
                            <a class="navbar-brand"><strong>Buscar</strong></a>
                            <input class="form-control mr-ms-2 text-indigo" type="search" placeholder="Buscar" id="search" aria-label="Buscar"
                                value="{{(isset($_GET['search']))?$_GET['search']:''}}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col">
                 <h3 class="mb-0">
                    <i class="fas fa-user text-primary"></i> Usuarios
                </h3>
            </div>
            <div class="col text-right">
                <a href="{{url('/users/create')}}" class="btn btn-sm btn-primary">Nuevo usuario</a>
                
                <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
                    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                        <div class="modal-content bg-gradient-success">
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-notification" aria-hidden="true">ALERTA</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="py-3 text-center">
                                    <i class="ni ni-bell-55 ni-3x"></i>
                                    <h4 class="heading mt-4">Desea generar pdf!</h4>
                                    <p>Esta de acuerdo con generar un pdf con los datos de la tabla especilidad</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        {{-- @if(session('notification'))
            <div class="alert alert-success" role="alert">
                {{ session('notification') }}
            </div>
        @endif
        @if(session('notifications'))
            <div class="alert alert-danger" role="alert">
                {{ session('notifications') }}
            </div>
        @endif --}}
    </div>
    
    <div class="table-responsive">
        <table class="table align-items-center table-flush text-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col" class="text-primary"><strong>Nº</strong></th>
                    <th scope="col" class="text-primary"><strong>Nombre</strong></th>
                    <th scope="col" class="text-primary"><strong>Apellido paterno</strong></th>
                    <th scope="col" class="text-primary"><strong>Apellido materno</strong></th>
                    <th scope="col" class="text-primary"><strong>Teléfono</strong></th>
                    <th scope="col" class="text-primary"><strong>Estado</strong></th>
                    <th scope="col" class="text-primary"><strong>Opciones</strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $valor=1;
                if($pag!=1){
                    $valor=$comienzo+1;
                }
                ?>
                @foreach ($users as $user)
                <tr>
                    <th>{{$valor++}}</th>
                    <th><strong>{{$user->name}}</strong></th>
                    <th><strong>{{$user->apaterno}}</strong></th>
                    <th><strong>{{$user->amaterno}}</strong></th>
                    <th><strong>{{$user->telefono}}</strong></th>
                   
                    <th>
                        @if($user->status=='inactivo')
                            <span class="badge badge-danger"><strong>{{$user->status}}</strong></span>
                        @else
                            <span class="badge badge-success"><strong>{{$user->status}}</strong></span>
                        @endif
                    </th>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{url('/users/'.Crypt::encrypt($user->id).'/edit')}}" class="btn btn-sm btn-primary" title="Editar usuario">
                                <i class="fas fa-edit"></i> 
                            </a>

                            @if($user->status=='inactivo')
                                <form action="{{ route('users.activo', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Activar este usuario?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" title="Activar usuario"><i class="ni ni-check-bold"></i></button>
                                </form>
                            @endif

                            @if($user->status=='activo')
                                <form action="{{ route('users.inactivo', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Inactivar este usuario?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-warning" title="Inactivar usuario"><i class="fas fa-ban"></i></button>
                                </form>
                            @endif
                            
                            <a href="{{url('/users/'.Crypt::encrypt($user->id))}}" class="btn btn-sm btn-info" title="Ver detalles">
                                <i class="fas fa-eye"></i> 
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($users->isEmpty())
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        <i class="ni ni-fat-remove"></i> No hay usuarios registrados
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="card-body pagination justify-content-end">
        @if($users->total() > 10)
            {{ $users->appends(request()->query())->links() }}
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $('#limit').on('change', function(){
        window.location.href = "{{ route('users.index') }}?limit=" + $(this).val() + '&search=' + $('#search').val()
    })
    
    $('#search').on('keyup', function(e){
        if(e.keyCode == 13){
            window.location.href = "{{ route('users.index') }}?limit=" + $('#limit').val() + '&search=' + $(this).val()
        }
    })
    
    @if (Session::has('toastr_success'))
        toastr.success("{{ Session::get('toastr_success') }}");
    @endif

    @if (Session::has('toastr_error'))
        toastr.error("{{ Session::get('toastr_error') }}");
    @endif

    @if (Session::has('toastr_warning'))
        toastr.warning("{{ Session::get('toastr_warning') }}");
    @endif

    @if (Session::has('toastr_info'))
        toastr.info("{{ Session::get('toastr_info') }}");
    @endif
    
    @if (session('notification'))
        toastr.success("{{ session('notification') }}");
    @endif
    
    @if (session('notifications'))
        toastr.error("{{ session('notifications') }}");
    @endif
</script>
@endsection