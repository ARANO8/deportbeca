@extends('layouts.panel')

@section('title', 'Mi Perfil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        {{-- DATOS DEL PERFIL --}}
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-user-edit"></i> Editar Perfil
            </div>
            <div class="card-body">

                @if(session('updatePerfil'))
                <div class="alert alert-success">{{ session('updatePerfil') }}</div>
                @endif

                @if(session('name'))
                <div class="alert alert-success">{{ session('name') }}</div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('perfil.actualizar') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            @php $user = auth()->user(); @endphp
                            @if($user->foto)
                            <img src="{{ asset('storage/perfiles/'.$user->foto) }}"
                                 alt="Foto de perfil"
                                 class="rounded-circle img-thumbnail mb-2"
                                 style="width:100px;height:100px;object-fit:cover;">
                            @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-2 mx-auto"
                                 style="width:100px;height:100px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                            @endif
                            <div>
                                <label class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-camera"></i> Cambiar foto
                                    <input type="file" name="foto" class="d-none" accept="image/jpeg,image/png,image/webp">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Correo electronico</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Apellido paterno</label>
                                    <input type="text" name="apaterno" class="form-control"
                                           value="{{ old('apaterno', $user->apaterno) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Apellido materno</label>
                                    <input type="text" name="amaterno" class="form-control"
                                           value="{{ old('amaterno', $user->amaterno) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Telefono</label>
                                    <input type="text" name="telefono" class="form-control"
                                           value="{{ old('telefono', $user->telefono) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CAMBIO DE CONTRASENA --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-lock"></i> Cambiar Contrasena
            </div>
            <div class="card-body">

                @if(session('updateClave'))
                <div class="alert alert-success">{{ session('updateClave') }}</div>
                @endif
                @if(session('clavemenor'))
                <div class="alert alert-warning">{{ session('clavemenor') }}</div>
                @endif
                @if(session('claveIncorrecta'))
                <div class="alert alert-danger">{{ session('claveIncorrecta') }}</div>
                @endif
                @if($errors->has('password_actual'))
                <div class="alert alert-danger">{{ $errors->first('password_actual') }}</div>
                @endif

                <form method="POST" action="{{ route('changePassword') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Contrasena actual</label>
                        <input type="password" name="password_actual" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva contrasena</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimo 8 caracteres y al menos un caracter especial.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar nueva contrasena</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-key"></i> Cambiar contrasena
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
