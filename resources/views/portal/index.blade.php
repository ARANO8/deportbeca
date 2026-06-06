@extends('layouts.portal')

@section('title', 'Portal de Resultados')

@section('styles')
<style>
    .portal-hero {
        text-align: center;
        padding: 48px 0 36px;
    }
    .portal-hero h1 {
        font-size: 2.4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #dc2626 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 10px;
    }
    .portal-hero p {
        color: #94a3b8;
        font-size: 1rem;
    }

    .eventos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 32px;
    }

    .evento-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.25s;
        display: flex;
        flex-direction: column;
    }

    .evento-card:hover {
        border-color: #dc2626;
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.4);
    }

    .evento-card-header {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        padding: 20px 24px;
    }

    .evento-card-header h3 {
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .evento-card-header .tipo-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .evento-card-body {
        padding: 20px 24px;
        flex: 1;
    }

    .evento-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .evento-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #94a3b8;
    }

    .evento-meta-item i {
        color: #dc2626;
        width: 14px;
    }

    .evento-series-count {
        background: rgba(220,38,38,0.1);
        border: 1px solid rgba(220,38,38,0.2);
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #f87171;
        margin-bottom: 16px;
    }

    .evento-actions {
        display: flex;
        gap: 8px;
    }

    .btn-portal {
        flex: 1;
        text-align: center;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-portal-primary {
        background: #dc2626;
        color: white;
        border: 1px solid transparent;
    }

    .btn-portal-primary:hover {
        background: #b91c1c;
        color: white;
    }

    .btn-portal-secondary {
        background: transparent;
        color: #94a3b8;
        border: 1px solid #334155;
    }

    .btn-portal-secondary:hover {
        border-color: #dc2626;
        color: #f87171;
    }

    .empty-portal {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-portal i {
        font-size: 3.5rem;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    /* Verificar box */
    .verify-box {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 24px 28px;
        margin-top: 40px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .verify-box .icon {
        font-size: 2.5rem;
        color: #3b82f6;
        flex-shrink: 0;
    }

    .verify-box h4 { color: white; font-weight: 700; margin-bottom: 4px; }
    .verify-box p  { color: #94a3b8; font-size: 0.85rem; margin: 0; }

    .verify-form {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .verify-form input {
        flex: 1;
        min-width: 200px;
        background: rgba(255,255,255,0.06);
        border: 1px solid #334155;
        border-radius: 10px;
        padding: 9px 14px;
        color: white;
        font-size: 0.85rem;
    }

    .verify-form input:focus {
        outline: none;
        border-color: #3b82f6;
        background: rgba(59,130,246,0.08);
    }

    .verify-form button {
        background: #3b82f6;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .verify-form button:hover { background: #2563eb; }
</style>
@endsection

@section('content')

<form method="GET" action="{{ url('/resultados') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control"
               placeholder="Buscar evento, disciplina..."
               value="{{ $busqueda ?? '' }}">
        <button class="btn btn-danger" type="submit">
            <i class="fas fa-search"></i> Buscar
        </button>
        @if(!empty($busqueda))
        <a href="{{ url('/resultados') }}" class="btn btn-outline-secondary">
            <i class="fas fa-times"></i> Limpiar
        </a>
        @endif
    </div>
</form>

<div class="portal-hero">
    <h1><i class="fas fa-trophy mr-2"></i>Portal de Resultados</h1>
    <p>Consulta tablas de posiciones y resultados sin necesidad de iniciar sesion</p>
</div>

@if($eventos->count() > 0)
<div class="eventos-grid">
    @foreach($eventos as $evento)
    @if(empty($busqueda) || str_contains(strtolower($evento->nombre), strtolower($busqueda)))
    <div class="evento-card">
        <div class="evento-card-header">
            <h3>{{ $evento->nombre }}</h3>
            <span class="tipo-badge">{{ ucfirst($evento->tipo_evento) }}</span>
        </div>
        <div class="evento-card-body">
            <div class="evento-meta">
                @if($evento->fecha_inicio)
                <div class="evento-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    Inicio: {{ $evento->fecha_inicio->format('d/m/Y') }}
                    @if($evento->fecha_fin)
                        &mdash; Fin: {{ $evento->fecha_fin->format('d/m/Y') }}
                    @endif
                </div>
                @endif
                @if($evento->descripcion)
                <div class="evento-meta-item">
                    <i class="fas fa-info-circle"></i>
                    {{ Str::limit($evento->descripcion, 70) }}
                </div>
                @endif
            </div>

            <div class="evento-series-count">
                <i class="fas fa-layer-group"></i>
                {{ $evento->series_count }} {{ $evento->series_count === 1 ? 'serie disponible' : 'series disponibles' }}
            </div>

            <div class="evento-actions">
                <a href="{{ route('portal.evento', $evento->id) }}" class="btn-portal btn-portal-primary">
                    <i class="fas fa-table"></i> Ver Posiciones
                </a>
                <a href="{{ route('portal.fixture', $evento->id) }}" class="btn-portal btn-portal-secondary">
                    <i class="fas fa-calendar-week"></i> Fixture
                </a>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>
@else
<div class="empty-portal">
    <i class="fas fa-trophy"></i>
    <h4 style="color: #f1f5f9; margin-bottom: 8px;">No hay eventos disponibles</h4>
    <p>Los resultados apareceran aqui una vez que comience la competencia.</p>
</div>
@endif

<!-- Caja de verificacion de inscripcion -->
<div class="verify-box">
    <div class="icon"><i class="fas fa-id-card-alt"></i></div>
    <div style="flex: 1;">
        <h4>Verificar estado de inscripcion</h4>
        <p>Ingresa tu codigo de inscripcion para consultar el estado de tu pre-inscripcion.</p>
        <form class="verify-form" onsubmit="verificarCodigo(event)">
            <input type="text" id="codigoInput" placeholder="Ej: INS-ABC12345" maxlength="20">
            <button type="submit"><i class="fas fa-search mr-1"></i> Verificar</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function verificarCodigo(e) {
    e.preventDefault();
    const codigo = document.getElementById('codigoInput').value.trim();
    if (!codigo) return;
    window.location.href = '{{ route("preinscripcion.verificar.form") }}/' + encodeURIComponent(codigo);
}
</script>
@endsection
