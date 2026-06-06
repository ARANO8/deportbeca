<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tabla de Posiciones — {{ $serie->nombre_serie }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #212529; }

        .header {
            background: #dc2626; color: white;
            padding: 14px 18px; margin-bottom: 18px; border-radius: 4px;
        }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 3px; }
        .header p  { font-size: 9px; opacity: .85; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #374151; color: white; padding: 7px 8px;
            font-size: 9px; text-transform: uppercase; letter-spacing: .05em;
            text-align: center;
        }
        thead th:nth-child(2) { text-align: left; }

        tbody tr:nth-child(even)  { background: #f9fafb; }
        tbody tr:nth-child(1)     { background: #fef9c3; } /* 1er lugar */
        tbody td {
            padding: 7px 8px; border-bottom: 1px solid #e5e7eb;
            text-align: center; vertical-align: middle;
        }
        tbody td:nth-child(2) { text-align: left; font-weight: 600; }

        .pos-badge {
            display: inline-block; width: 22px; height: 22px;
            border-radius: 50%; line-height: 22px; font-weight: bold;
            font-size: 10px; text-align: center;
        }
        .pos-1 { background: #fbbf24; color: white; }
        .pos-2 { background: #9ca3af; color: white; }
        .pos-3 { background: #b45309; color: white; }
        .pos-other { background: #f3f4f6; color: #374151; }
        .clasifica { color: #15803d; font-weight: bold; }

        .pts-cell { font-weight: bold; font-size: 12px; color: #dc2626; }
        .ta-cell   { color: #d97706; }
        .tr-cell   { color: #dc2626; }

        .legend {
            margin-top: 14px; font-size: 9px; color: #6b7280;
            border-top: 1px solid #e5e7eb; padding-top: 8px;
        }
        .clasifica-legend {
            display: inline-block; width: 10px; height: 10px;
            background: #d1fae5; border: 1px solid #6ee7b7;
            margin-right: 4px; vertical-align: middle;
        }
        .footer {
            margin-top: 20px; padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px; color: #9ca3af; text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>TABLA DE POSICIONES — {{ strtoupper($serie->nombre_serie) }}</h1>
    <p>
        {{ $serie->disciplina->nombre ?? '' }}
        &nbsp;·&nbsp; {{ $serie->eventoConfiguracion->nombre ?? '' }}
        &nbsp;·&nbsp; Clasifican: {{ $serie->cuantos_clasifican }}
        &nbsp;·&nbsp; Generado: {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th style="width:5%;">#</th>
            <th style="width:30%;">Equipo</th>
            <th style="width:5%;" title="Partidos Jugados">PJ</th>
            <th style="width:5%;" title="Ganados">PG</th>
            <th style="width:5%;" title="Empatados">PE</th>
            <th style="width:5%;" title="Perdidos">PP</th>
            <th style="width:5%;" title="Goles a Favor">GF</th>
            <th style="width:5%;" title="Goles en Contra">GC</th>
            <th style="width:6%;" title="Diferencia">DG</th>
            <th style="width:5%;" title="Tarjetas Amarillas">TA</th>
            <th style="width:5%;" title="Tarjetas Rojas">TR</th>
            <th style="width:6%;" title="Puntos">PTS</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tablaPosiciones as $idx => $stat)
            @php
                $pos       = $idx + 1;
                $clasifica = $pos <= $serie->cuantos_clasifican;
                $nombre    = $stat->nombre_equipo ?: ($stat->equipo->nombre_participante ?? '—');
                $carrera   = $stat->equipo?->carrera?->nombre ?? $stat->equipo?->facultad?->nombre ?? '';
                $badgeClass = match($pos) { 1 => 'pos-1', 2 => 'pos-2', 3 => 'pos-3', default => 'pos-other' };
            @endphp
            <tr style="{{ $clasifica ? 'background:#f0fdf4;' : '' }}">
                <td>
                    <span class="pos-badge {{ $badgeClass }}">{{ $pos }}</span>
                </td>
                <td>
                    <span class="{{ $clasifica ? 'clasifica' : '' }}">{{ $nombre }}</span>
                    @if($carrera) <br><span style="font-size:8px; color:#6b7280; font-weight:normal;">{{ $carrera }}</span> @endif
                </td>
                <td>{{ $stat->pj }}</td>
                <td>{{ $stat->pg }}</td>
                <td>{{ $stat->pe }}</td>
                <td>{{ $stat->pp }}</td>
                <td>{{ $stat->gf }}</td>
                <td>{{ $stat->gc }}</td>
                <td>{{ ($stat->dg >= 0 ? '+' : '') . $stat->dg }}</td>
                <td class="ta-cell">{{ $stat->tarjetas_amarillas }}</td>
                <td class="tr-cell">{{ $stat->tarjetas_rojas }}</td>
                <td class="pts-cell">{{ $stat->pts }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12" style="text-align:center; padding:20px; color:#9ca3af; font-style:italic;">
                    Sin resultados registrados para esta serie.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="legend">
    <span class="clasifica-legend"></span> Equipos clasificados a la siguiente fase
    &nbsp;·&nbsp; PJ=Jugados · PG=Ganados · PE=Empatados · PP=Perdidos · GF=Goles Favor · GC=Goles Contra · DG=Diferencia · TA=Amarillas · TR=Rojas · PTS=Puntos
</div>

<div class="footer">
    DeportBeca — Sistema de Gestión Deportiva Universitaria &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>
