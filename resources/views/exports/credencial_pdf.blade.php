<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; }
    .credencial {
        width: 100%;
        border: 3px solid #dc2626;
        border-radius: 8px;
        overflow: hidden;
    }
    .header {
        background: #dc2626;
        color: white;
        padding: 10px 16px;
        display: table;
        width: 100%;
    }
    .header-left { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; text-align: right; vertical-align: middle; font-size: 9px; opacity: 0.9; }
    .header h1 { font-size: 16px; font-weight: bold; letter-spacing: 1px; }
    .header p { font-size: 9px; opacity: 0.9; margin-top: 2px; }
    .body { padding: 12px 16px; display: table; width: 100%; }
    .col-left { display: table-cell; width: 70%; vertical-align: top; padding-right: 16px; }
    .col-right { display: table-cell; width: 30%; vertical-align: top; text-align: center; }
    .foto-box {
        width: 80px; height: 80px;
        border: 2px solid #dc2626;
        border-radius: 6px;
        overflow: hidden;
        margin: 0 auto;
    }
    .foto-box img { width: 100%; height: 100%; object-fit: cover; }
    .foto-placeholder {
        width: 80px; height: 80px;
        border: 2px dashed #dc2626;
        border-radius: 6px;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        color: #9ca3af;
        font-size: 8px;
    }
    .field { margin-bottom: 6px; }
    .field-label { font-size: 8px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1px; }
    .field-value { font-size: 12px; font-weight: bold; color: #111827; }
    .badges { margin-top: 8px; }
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: bold;
        margin-right: 4px;
    }
    .badge-green { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .badge-blue  { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .footer {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 6px 16px;
        display: table;
        width: 100%;
        font-size: 8px;
        color: #6b7280;
    }
    .footer-left  { display: table-cell; vertical-align: middle; }
    .footer-right { display: table-cell; text-align: right; vertical-align: middle; }
    .codigo { font-size: 13px; font-weight: bold; color: #dc2626; letter-spacing: 1px; }
    .integrantes { margin-top: 10px; }
    .integrantes-title { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .integrante-item { font-size: 10px; padding: 2px 0; border-bottom: 1px solid #f3f4f6; }
    .integrante-item:last-child { border-bottom: none; }
    .capitan { color: #dc2626; font-weight: bold; }
</style>
</head>
<body>
<div class="credencial">
    <div class="header">
        <div class="header-left">
            <h1>CREDENCIAL DE PARTICIPACION</h1>
            <p>{{ strtoupper($preinscripcion->tipo_evento) }} &mdash; {{ $preinscripcion->disciplina->nombre ?? 'Disciplina' }}</p>
        </div>
        <div class="header-right">
            Generado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="body">
        <div class="col-left">
            <div class="field">
                <div class="field-label">Nombre del equipo / participante</div>
                <div class="field-value">
                    {{ $preinscripcion->tipo_inscripcion === 'individual'
                        ? $preinscripcion->representante_nombre
                        : ($preinscripcion->nombre_equipo ?? $preinscripcion->representante_nombre) }}
                </div>
            </div>

            <div class="field">
                <div class="field-label">Capitan / Representante</div>
                <div class="field-value">{{ $preinscripcion->representante_nombre }}</div>
            </div>

            @if($preinscripcion->facultad)
            <div class="field">
                <div class="field-label">Facultad</div>
                <div class="field-value">{{ $preinscripcion->facultad->nombre }}</div>
            </div>
            @endif

            @if($preinscripcion->carrera)
            <div class="field">
                <div class="field-label">Carrera</div>
                <div class="field-value">{{ $preinscripcion->carrera->nombre }}</div>
            </div>
            @endif

            <div class="badges">
                <span class="badge badge-green">HABILITADO</span>
                <span class="badge badge-blue">{{ strtoupper($preinscripcion->tipo_inscripcion) }}</span>
            </div>

            @if($preinscripcion->tipo_inscripcion === 'grupal' && $preinscripcion->integrantes->count() > 0)
            <div class="integrantes">
                <div class="integrantes-title">Integrantes ({{ $preinscripcion->integrantes->count() }})</div>
                @foreach($preinscripcion->integrantes as $integrante)
                <div class="integrante-item {{ $integrante->es_capitan ? 'capitan' : '' }}">
                    {{ $integrante->nombre }}
                    @if($integrante->es_capitan) (Capitan) @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-right">
            <div class="codigo">{{ $preinscripcion->codigo_inscripcion }}</div>
            <div style="font-size:8px;color:#9ca3af;margin-top:2px;">Codigo de inscripcion</div>
            <div style="margin-top:12px;">
                <div class="foto-placeholder" style="display:table;width:80px;height:80px;border:2px dashed #dc2626;border-radius:6px;margin:0 auto;">
                    <div style="display:table-cell;vertical-align:middle;text-align:center;font-size:8px;color:#9ca3af;">
                        Sin foto
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-left">
            Contacto: {{ $preinscripcion->representante_email }}
            &nbsp;&bull;&nbsp;
            Tel: {{ $preinscripcion->representante_telefono }}
        </div>
        <div class="footer-right">
            DeportBeca &mdash; Sistema de Gestion Deportiva
        </div>
    </div>
</div>
</body>
</html>
