@extends('layouts.panel')

@section('title', 'Editar Partido')

@section('content')
<div class="container-fluid">
    <div class="card shadow" style="border-radius: 16px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-edit mr-2"></i>Editar Partido</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('fixture.actualizar.partido', $partido->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6"><div class="form-group"><label>Fecha</label><input type="date" name="fecha" class="form-control" value="{{ $partido->fecha ? date('Y-m-d', strtotime($partido->fecha)) : '' }}"></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Hora</label><input type="time" name="hora_inicio" class="form-control" value="{{ $partido->hora_inicio ? substr($partido->hora_inicio,0,5) : '' }}"></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Lugar</label><select name="lugar_id" class="form-control"><option value="">Seleccionar</option>@foreach($lugares as $l)<option value="{{ $l->id }}" @if($partido->lugar_id == $l->id) selected @endif>{{ $l->nombre }}</option>@endforeach</select></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Estado</label><select name="estado" class="form-control"><option value="programado" @if($partido->estado=='programado') selected @endif>Programado</option><option value="en_curso" @if($partido->estado=='en_curso') selected @endif>En curso</option><option value="finalizado" @if($partido->estado=='finalizado') selected @endif>Finalizado</option><option value="suspendido" @if($partido->estado=='suspendido') selected @endif>Suspendido</option><option value="cancelado" @if($partido->estado=='cancelado') selected @endif>Cancelado</option></select></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Goles Local</label><input type="number" name="goles_local" class="form-control" min="0" value="{{ $partido->goles_local }}"></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Goles Visitante</label><input type="number" name="goles_visitante" class="form-control" min="0" value="{{ $partido->goles_visitante }}"></div></div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Actualizar</button>
                    <a href="{{ route('fixture.ver', $partido->evento_configuracion_id) }}" class="btn btn-secondary"><i class="fas fa-times mr-2"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection