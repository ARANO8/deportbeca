{{-- resources/views/fixture/imprimir.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fixture - {{ $evento->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { color: #2c3e50; }
        .serie-card { page-break-inside: avoid; margin-bottom: 30px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .serie-header { background: #2c3e50; color: white; padding: 10px 15px; }
        .partido-row { padding: 10px 15px; border-bottom: 1px solid #eee; }
        .partido-row:last-child { border-bottom: none; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>FIXTURE OFICIAL</h1>
        <h2>{{ $evento->nombre }}</h2>
        <p>Fecha de emisión: {{ date('d/m/Y H:i') }}</p>
    </div>

    @foreach($series as $serie)
    <div class="serie-card">
        <div class="serie-header">
            <h4>{{ $serie->nombre_serie }}</h4>
            <small>{{ $serie->disciplina->nombre ?? 'Disciplina' }} | {{ $serie->cantidad_equipos }} equipos</small>
        </div>
        <div class="serie-body">
            @foreach($serie->partidos->groupBy('jornada') as $jornada => $partidos)
            <div style="padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #ddd;">
                <strong>Jornada {{ $jornada }}</strong>
            </div>
            @foreach($partidos as $partido)
            <div class="partido-row">
                <div class="row align-items-center">
                    <div class="col-5 text-right">
                        <strong>{{ $partido->equipoLocal->nombre_participante ?? 'Local' }}</strong>
                    </div>
                    <div class="col-2 text-center">
                        @if($partido->goles_local !== null)
                            <span style="font-weight: bold;">{{ $partido->goles_local }} - {{ $partido->goles_visitante }}</span>
                        @else
                            <span>VS</span>
                        @endif
                    </div>
                    <div class="col-5 text-left">
                        <strong>{{ $partido->equipoVisitante->nombre_participante ?? 'Visitante' }}</strong>
                    </div>
                    <div class="col-12 mt-2">
                        <small>📅 {{ $partido->fecha ? date('d/m/Y', strtotime($partido->fecha)) : 'Fecha por definir' }} | 🕐 {{ $partido->hora_inicio ? substr($partido->hora_inicio, 0, 5) : '--:--' }} hrs | 📍 {{ $partido->lugar->nombre ?? 'Lugar por definir' }}</small>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>Sistema de Gestión Deportiva - Fixture generado automáticamente</p>
    </div>
</body>
</html>