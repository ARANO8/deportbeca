<!DOCTYPE html>
<html>
<head>
    <title>Inscripción con Observaciones</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #f59e0b, #dc2626); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 28px;">📋 OBSERVACIONES</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">
                @if($tipo_inscripcion == 'individual')
                    Tu inscripción requiere correcciones
                @else
                    Tu equipo requiere correcciones
                @endif
            </p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <p style="font-size: 18px; color: #333;">Estimado <strong>{{ $nombre_capitan }}</strong>,</p>
            
            @if($tipo_inscripcion == 'individual')
                <p>Hemos revisado tu <strong>inscripción individual</strong> y tiene las siguientes <span style="color: #dc2626; font-weight: bold;">observaciones</span> que debes corregir:</p>
            @else
                <p>Hemos revisado la inscripción de tu equipo <strong>{{ $nombre_equipo }}</strong> y tiene las siguientes <span style="color: #dc2626; font-weight: bold;">observaciones</span> que debes corregir:</p>
            @endif
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 0; color: #856404;">
                    <strong>Motivo de la observación:</strong><br>
                    {{ $motivo }}
                </p>
            </div>
            
            <div style="background: #e8f0fe; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>📅 Evento:</strong> {{ $tipo_evento }}</p>
                <p style="margin: 5px 0;"><strong>🏅 Disciplina:</strong> {{ $disciplina }}</p>
                <p style="margin: 5px 0;"><strong>🔑 Código de inscripción:</strong> <code style="background: #fff; padding: 2px 6px; border-radius: 4px;">{{ $codigo }}</code></p>
                @if($tipo_inscripcion == 'grupal')
                    <p style="margin: 5px 0;"><strong>👥 Integrantes:</strong> {{ $cantidad_integrantes }} personas</p>
                @endif
            </div>
            
            <p>Por favor, corrige las observaciones indicadas. Para cualquier consulta, comunícate con la organización.</p>
            
            <p>Quedamos atentos a tu respuesta.</p>
        </div>
        
        <!-- Footer -->
        <div style="background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #ddd;">
            <p style="margin: 0; color: #666; font-size: 12px;">
                Este es un mensaje automático de DeportBeca.<br>
                División de Becas y Deportes - UMSA
            </p>
        </div>
    </div>
</body>
</html>