@extends('layouts.portal')

@section('title', 'Verificar Inscripcion')

@section('styles')
<style>
    .verify-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 24px 0;
    }

    .verify-title {
        text-align: center;
        margin-bottom: 28px;
    }
    .verify-title h1 {
        font-size: 1.7rem;
        font-weight: 800;
        color: #f1f5f9;
        margin-bottom: 6px;
    }
    .verify-title p { color: #64748b; font-size: 0.85rem; }

    /* Buscador */
    .search-box {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 28px;
    }
    .search-box label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #94a3b8;
        display: block;
        margin-bottom: 8px;
    }
    .search-row {
        display: flex;
        gap: 8px;
    }
    .search-row input {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1px solid #475569;
        border-radius: 10px;
        padding: 10px 14px;
        color: #f1f5f9;
        font-size: 0.88rem;
    }
    .search-row input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    .search-row button {
        background: #3b82f6;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .search-row button:hover { background: #2563eb; }

    /* Card de resultado */
    .result-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 18px;
        overflow: hidden;
    }

    .result-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .result-header.habilitado  { background: rgba(16,185,129,0.12); border-bottom: 1px solid rgba(16,185,129,0.2); }
    .result-header.pendiente   { background: rgba(245,158,11,0.1);  border-bottom: 1px solid rgba(245,158,11,0.2); }
    .result-header.observado   { background: rgba(220,38,38,0.1);   border-bottom: 1px solid rgba(220,38,38,0.2); }

    .result-estado {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .estado-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .icon-habilitado { background: rgba(16,185,129,0.15); color: #10b981; }
    .icon-pendiente  { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .icon-observado  { background: rgba(220,38,38,0.15);  color: #f87171; }

    .estado-label h3 { font-size: 0.9rem; font-weight: 700; color: #f1f5f9; margin: 0 0 2px; }
    .estado-label p  { font-size: 0.75rem; color: #64748b; margin: 0; }

    .codigo-badge {
        font-family: monospace;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        letter-spacing: 1px;
    }
    .codigo-habilitado { background: rgba(16,185,129,0.15); color: #10b981; }
    .codigo-pendiente  { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .codigo-observado  { background: rgba(220,38,38,0.15);  color: #f87171; }

    .result-body {
        padding: 22px 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 20px;
    }

    .info-item label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 3px;
    }
    .info-item span {
        font-size: 0.88rem;
        color: #e2e8f0;
        font-weight: 500;
    }

    .observacion-box {
        background: rgba(220,38,38,0.06);
        border: 1px solid rgba(220,38,38,0.2);
        border-radius: 10px;
        padding: 14px 16px;
        margin-top: 16px;
    }
    .observacion-box label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #f87171;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 4px;
    }
    .observacion-box p {
        font-size: 0.84rem;
        color: #fca5a5;
        margin: 0;
    }

    /* QR Section */
    .qr-section {
        border-top: 1px solid #334155;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .qr-container {
        background: white;
        border-radius: 10px;
        padding: 10px;
        display: inline-block;
        flex-shrink: 0;
    }
    .qr-info h5 {
        font-size: 0.85rem;
        font-weight: 700;
        color: #f1f5f9;
        margin-bottom: 4px;
    }
    .qr-info p {
        font-size: 0.76rem;
        color: #64748b;
        margin: 0 0 10px;
    }
    .btn-copy {
        background: transparent;
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 6px 14px;
        color: #94a3b8;
        font-size: 0.76rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-copy:hover { border-color: #3b82f6; color: #60a5fa; }

    /* Error card */
    .error-card {
        background: rgba(220,38,38,0.06);
        border: 1px solid rgba(220,38,38,0.25);
        border-radius: 14px;
        padding: 32px;
        text-align: center;
    }
    .error-card i { font-size: 2.5rem; color: #f87171; margin-bottom: 12px; }
    .error-card h3 { color: #f1f5f9; margin-bottom: 6px; }
    .error-card p  { color: #64748b; font-size: 0.85rem; }

    @media (max-width: 560px) {
        .info-grid { grid-template-columns: 1fr; }
        .result-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('content')
<div class="verify-wrap">

    <div class="verify-title">
        <h1><i class="fas fa-id-card-alt mr-2" style="color:#3b82f6;"></i>Verificar Inscripcion</h1>
        <p>Consulta el estado de tu pre-inscripcion ingresando tu codigo</p>
    </div>

    <!-- Formulario de busqueda -->
    <div class="search-box">
        <label>Codigo de inscripcion</label>
        <form class="search-row" action="{{ url('/preinscripcion/verificar') }}" method="GET" onsubmit="return irACodigo(event)">
            <input type="text" id="codigoInput"
                   placeholder="Ej: INS-ABC12345"
                   value="{{ isset($inscripcion) ? $inscripcion->codigo_inscripcion : ($codigo ?? '') }}"
                   maxlength="20" autocomplete="off">
            <button type="submit"><i class="fas fa-search mr-1"></i> Buscar</button>
        </form>
    </div>

    @if(isset($inscripcion) && $inscripcion)
    @php
        $estado = $inscripcion->estado;
        if ($estado === 'habilitado') {
            $hClase = 'habilitado'; $iClase = 'icon-habilitado'; $bClase = 'codigo-habilitado';
            $estadoIcon = 'fas fa-check-circle'; $estadoTexto = 'Inscripcion Habilitada';
            $estadoDesc = 'Tu inscripcion ha sido aprobada y estas habilitado para competir.';
        } elseif ($estado === 'observado') {
            $hClase = 'observado'; $iClase = 'icon-observado'; $bClase = 'codigo-observado';
            $estadoIcon = 'fas fa-exclamation-triangle'; $estadoTexto = 'Inscripcion con Observaciones';
            $estadoDesc = 'Tu inscripcion requiere correccion. Revisa las observaciones a continuacion.';
        } else {
            $hClase = 'pendiente'; $iClase = 'icon-pendiente'; $bClase = 'codigo-pendiente';
            $estadoIcon = 'fas fa-clock'; $estadoTexto = 'Revision Pendiente';
            $estadoDesc = 'Tu inscripcion esta en proceso de revision. Te notificaremos pronto.';
        }
    @endphp

    <div class="result-card">
        <!-- Header con estado -->
        <div class="result-header {{ $hClase }}">
            <div class="result-estado">
                <div class="estado-icon {{ $iClase }}">
                    <i class="{{ $estadoIcon }}"></i>
                </div>
                <div class="estado-label">
                    <h3>{{ $estadoTexto }}</h3>
                    <p>{{ $estadoDesc }}</p>
                </div>
            </div>
            <span class="codigo-badge {{ $bClase }}">{{ $inscripcion->codigo_inscripcion }}</span>
        </div>

        <!-- Detalle de inscripcion -->
        <div class="result-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Representante</label>
                    <span>{{ $inscripcion->representante_nombre }}</span>
                </div>
                <div class="info-item">
                    <label>Disciplina</label>
                    <span>{{ $inscripcion->disciplina->nombre ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <label>Tipo de inscripcion</label>
                    <span>{{ ucfirst($inscripcion->tipo_inscripcion) }}</span>
                </div>
                <div class="info-item">
                    <label>Tipo de evento</label>
                    <span>{{ ucfirst($inscripcion->tipo_evento) }}</span>
                </div>
                @if($inscripcion->nombre_equipo)
                <div class="info-item">
                    <label>Nombre del equipo</label>
                    <span>{{ $inscripcion->nombre_equipo }}</span>
                </div>
                @endif
                @if($inscripcion->carrera)
                <div class="info-item">
                    <label>Carrera</label>
                    <span>{{ $inscripcion->carrera->nombre }}</span>
                </div>
                @elseif($inscripcion->facultad)
                <div class="info-item">
                    <label>Facultad</label>
                    <span>{{ $inscripcion->facultad->nombre }}</span>
                </div>
                @endif
                <div class="info-item">
                    <label>Fecha de registro</label>
                    <span>{{ $inscripcion->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            @if($inscripcion->estado === 'observado' && $inscripcion->observaciones)
            <div class="observacion-box">
                <label><i class="fas fa-exclamation-circle mr-1"></i> Observaciones del evaluador</label>
                <p>{{ $inscripcion->observaciones }}</p>
            </div>
            @endif
        </div>

        <!-- QR Section -->
        <div class="qr-section">
            <div class="qr-container">
                <div id="qrcode"></div>
            </div>
            <div class="qr-info">
                <h5>Compartir verificacion</h5>
                <p>Escanea el codigo QR o comparte el enlace para que otros puedan verificar tu inscripcion.</p>
                <button class="btn-copy" onclick="copiarEnlace()">
                    <i class="fas fa-copy"></i> Copiar enlace
                </button>
            </div>
        </div>
    </div>

    @elseif(isset($codigo) && $codigo)
    <!-- Codigo no encontrado -->
    <div class="error-card">
        <i class="fas fa-search"></i>
        <h3>Codigo no encontrado</h3>
        <p>No encontramos ninguna inscripcion con el codigo <strong style="color:#f87171;">{{ $codigo }}</strong>.</p>
        <p style="margin-top:8px;">Verifica que hayas ingresado el codigo correctamente (sensible a mayusculas).</p>
    </div>

    @else
    <!-- Estado inicial sin codigo -->
    <div style="text-align:center; padding:40px; color:#475569;">
        <i class="fas fa-qrcode" style="font-size:3rem; margin-bottom:16px; display:block; opacity:0.3;"></i>
        <p style="font-size:0.85rem;">Ingresa tu codigo de inscripcion en el campo de arriba para consultar tu estado.</p>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<!-- QR Code JS (sin dependencias de servidor) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function irACodigo(e) {
    e.preventDefault();
    const codigo = document.getElementById('codigoInput').value.trim();
    if (!codigo) return false;
    window.location.href = '{{ url("/preinscripcion/verificar") }}/' + encodeURIComponent(codigo);
    return false;
}

@if(isset($inscripcion) && $inscripcion)
const verifyUrl = '{{ url("/preinscripcion/verificar/" . $inscripcion->codigo_inscripcion) }}';
new QRCode(document.getElementById('qrcode'), {
    text: verifyUrl,
    width: 120,
    height: 120,
    colorDark: '#000000',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.M
});

function copiarEnlace() {
    navigator.clipboard.writeText(verifyUrl).then(function() {
        const btn = document.querySelector('.btn-copy');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copiado';
        btn.style.color = '#10b981';
        btn.style.borderColor = '#10b981';
        setTimeout(() => {
            btn.innerHTML = original;
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    }).catch(function() {
        prompt('Copia este enlace:', verifyUrl);
    });
}
@endif
</script>
@endsection
