<!DOCTYPE html>
<html>
<head>
    <title>Equipo Habilitado</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #dc2626, #2563eb); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 28px;"> INSCRIPCIÓN HABILITADA</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">
                @if($tipo_inscripcion == 'individual')
                    Tu inscripción individual ha sido aprobada
                @else
                    Tu equipo ha sido habilitado
                @endif
            </p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <p style="font-size: 18px; color: #333;">Estimado <strong>{{ $nombre_capitan }}</strong>,</p>
            
            @if($tipo_inscripcion == 'individual')
                <p>Nos complace informarte que tu <strong style="color: #2563eb;">inscripción individual</strong> ha sido <span style="color: #28a745; font-weight: bold;">HABILITADA</span> para participar en el evento.</p>
            @else
                <p>Nos complace informarte que tu equipo <strong style="color: #2563eb;">{{ $nombre_equipo }}</strong> ha sido <span style="color: #28a745; font-weight: bold;">HABILITADO</span> para participar en el evento.</p>
            @endif
            
            <div style="background: #e8f0fe; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>📅 Evento:</strong> {{ $tipo_evento }}</p>
                <p style="margin: 5px 0;"><strong>🏅 Disciplina:</strong> {{ $disciplina }}</p>
                <p style="margin: 5px 0;"><strong>🔑 Código de inscripción:</strong> <code style="background: #fff; padding: 2px 6px; border-radius: 4px;">{{ $codigo }}</code></p>
                <p style="margin: 5px 0;"><strong>📅 Fecha de habilitación:</strong> {{ $fecha }}</p>
                @if($tipo_inscripcion == 'grupal')
                    <p style="margin: 5px 0;"><strong>👥 Integrantes:</strong> {{ $cantidad_integrantes }} personas</p>
                @endif
            </div>
            
            <p style="color: #28a745; font-weight: bold;">
                @if($tipo_inscripcion == 'individual')
                    🎉 ¡Felicidades! Tu inscripción está confirmada.
                @else
                    🎉 ¡Felicidades! Tu equipo está listo para competir.
                @endif
            </p>
            
            <p>Te esperamos en el evento. Mucho éxito.</p>
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