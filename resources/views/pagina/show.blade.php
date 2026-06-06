@extends('layouts.panel')
@if(auth()->user()->esSuperAdmin())
@section('content')


     
      <div class="card shadow">
        <div class="card-header border-0">
          <div class="row align-items-center">
             <div class="col-xl-12">
             
       
      </div>
            <div class="col">
              <h3 class="mb-0">Nº de publicacion: {{$paginas->id}}</h3>
            </div>
            <div class="col text-right">
                <a href="{{url('/paginawebs')}}" class="btn btn-sm btn-success">
                  <i class="fas fa-chevron-left"></i>
                  Regresar</a>
              </div>
          </div>
        </div>
        <div class="card-body bg-light text-dark ">
        <h3>Datos del comunicado</h3>
            <ul>
                <dd>
                    <strong>Nombre:</strong> {{$paginas->nombre}}
                </dd>
                <dd>
                  <strong>Foto:</strong>
                   <br> <img src="{{asset('imagen/'.$paginas->imagen)}}" width="300px" height="300px">
              </dd>
                <dd>
                    <strong>Descripción:</strong> {{$paginas->descripcion}}
                </dd>
                
               
            </ul>

        </div>
       
      </div>
@endsection
@else
@section('content')
      <div class="card shadow">
        <div class="card-header border-0">
          <div class="row align-items-center">
             <div class="col-xl-12">
             
       
             </div>
            
          </div>
        </div>
        <div class="card-body">
          @if(session('notification'))
            <div class="alert alert-success" role="alert">
                {{ session('notification') }}
            </div>
        
          @endif
          @if(session('notifications'))
            <div class="alert alert-danger" role="alert">
                {{ session('notifications') }}
            </div>
        
          @endif
        </div>
        <div>
          <h1>USTED NO TIENE ACCESO A ESTA RUTA :( <a href="{{url('/home')}}" class="btn btn-success btn-sm">
            VOLVER
          </a></h1>
          
      </div>
      </div>
@endsection
@endif
