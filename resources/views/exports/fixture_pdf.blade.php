<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fixture — {{ $evento->nombre }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #212529; }

        .header {
            background: #dc2626; color: white;
            padding: 14px 18px; margin-bottom: 18px;
            border-radius: 4px;
        }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 3px; }
        .header p  { font-size: 10px; opacity: .85; }

        .serie-block { margin-bottom: 20px; page-break-inside: avoid; }
        .serie-title {
            background: #f1f5f9; padding: 7px 10px;
            font-weight: bold; font-size: 11px; color: #1f2937;
            border-left: 4px solid #dc2626; margin-bottom: 8px;
        }
        .serie-meta { font-size: 9px; color: #6b7280; margin-left: 10px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        thead th {
            background: #374151; color: white;
            padding: 5px 8px; font-size: 9px; text-transform: uppercase;
            letter-spacing: .05em; text-align: left;
        }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }

        .jornada-label {
            font-size: 9px; color: #dc2626; font-weight: bold;
            text-transform: uppercase; letter-spacing: .05em;
            padding: 3px 8px; background: #fef2f2;
        }
        .vs { color: #6b7280; font-weight: bold; }
        .resultado { font-weight: bold; color: #1f2937; }
        .sin-resultado { color: #9ca3af; font-style: italic; }

        .footer {
            margin-top: 24px; padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px; color: #9ca3af; text-align: center;
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

<div class="header">
    <h1>FIXTURE OFICIAL — {{ strtoupper($evento->nombre) }}</h1>
    <p>
        {{ ucfirst($evento->tipo_evento) }}
        @if($evento->fecha_inicio) &nbsp;·&nbsp; {{ $evento->fecha_inicio->format('d/m/Y') }} — {{ optional($evento->fecha_fin)->format('d/m/Y') }} @endif
        &nbsp;·&nbsp; Generado: {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

@forelse($series as $idx => $serie)
    @if($idx > 0 && $idx % 3 === 0)
        <div class="page-break"></div>
    @endif

    <div class="serie-block">
        <div class="serie-title">
            {{ $serie->nombre_serie }}
            <span class="serie-meta">
                {{ $serie->disciplina->nombre ?? '' }}
                &nbsp;·&nbsp; {{ $serie->cantidad_equipos }} equipo{{ $serie->cantidad_equipos !== 1 ? 's' : '' }}
                &nbsp;·&nbsp; Clasifican {{ $serie->cuantos_clasifican }}
            </span>
        </div>

        @php $jornadas = $serie->partidos->groupBy('jornada'); @endphp
        @forelse($jornadas as $jornada => $partidos)
        <table>
            <thead>
                <tr>
                    <th colspan="6" class="jornada-label">Jornada {{ $jornada }}</th>
                </tr>
                <tr>
                    <th style="width:30%;">Local</th>
                    <th style="width:8%; text-align:center;">VS</th>
                    <th style="width:30%;">Visitante</th>
                    <th style="width:12%;">Resultado</th>
                    <th style="width:10%;">Fecha</th>
                    <th style="width:10%;">Lugar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partidos as $partido)
                <tr>
                    <td>{{ $partido->equipoLocal->nombre_participante ?? 'Por definir' }}</td>
                    <td style="text-align:center;" class="vs">VS</td>
                    <td>{{ $partido->equipoVisitante->nombre_participante ?? 'Por definir' }}</td>
                    <td style="text-align:center;">
                        @if($partido->goles_local !== null && $partido->goles_visitante !== null)
                            <span class="resultado">{{ $partido->goles_local }} - {{ $partido->goles_visitante }}</span>
                        @else
                            <span class="sin-resultado">— - —</span>
                        @endif
                    </td>
                    <td>{{ $partido->fecha ? $partido->fecha->format('d/m/Y') : '—' }}</td>
                    <td>{{ $partido->lugar->nombre ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @empty
            <p style="color:#9ca3af; font-style:italic; padding: 4px 10px;">Sin partidos generados para esta serie.</p>
        @endforelse
    </div>
@empty
    <p style="color:#6b7280; text-align:center; padding: 30px;">No hay series generadas para este evento.</p>
@endforelse

<div class="footer">
    DeportBeca — Sistema de Gestión Deportiva Universitaria &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>
