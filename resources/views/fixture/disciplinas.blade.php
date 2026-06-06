
@extends('layouts.panel')

@section('title', 'Seleccionar Disciplina')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('eventos.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Eventos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-trophy mr-3"></i>
                {{ $evento->nombre }}
            </h1>
            <p class="text-blue-100 mt-1">
                <i class="fas fa-tag mr-1"></i> {{ ucfirst($evento->tipo_evento) }} |
                <i class="fas fa-calendar ml-2 mr-1"></i> 
                {{ $evento->fecha_inicio ? \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') : 'Por definir' }}
            </p>
        </div>

        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-dumbbell text-blue-600 mr-2"></i>
                Selecciona la disciplina
            </h2>

            @if(count($disciplinas) == 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3"></i>
                        <div>
                            <p class="text-yellow-700">No hay disciplinas configuradas para este evento.</p>
                            <a href="#" class="text-yellow-600 hover:text-yellow-800 text-sm mt-1 inline-block">
                                Configurar disciplinas →
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($disciplinas as $disciplina)
                        <a href="{{ route('fixture.participantes', [$evento->id, $disciplina['id']]) }}" 
                           class="group bg-gray-50 hover:bg-blue-50 border border-gray-200 rounded-lg p-5 transition-all card-hover">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-running text-blue-600 text-xl mr-3"></i>
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $disciplina['nombre'] }}</h3>
                                    </div>
                                    @if($disciplina['parent_nombre'])
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-folder-open mr-1"></i>
                                            Subdisciplina de: {{ $disciplina['parent_nombre'] }}
                                        </p>
                                    @endif
                                    <div class="mt-3 flex items-center text-blue-600 group-hover:text-blue-700">
                                        <span class="text-sm">Configurar fixture</span>
                                        <i class="fas fa-arrow-right ml-2 text-sm group-hover:translate-x-1 transition-all"></i>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-400 transition-all"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection