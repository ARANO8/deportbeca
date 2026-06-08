{{--
    Mapa de solo lectura con Leaflet + OpenStreetMap.
    Uso: @include('partials.map-display', ['id'=>'mapVerLugar','lat'=>$lugar->latitud,'lng'=>$lugar->longitud])
--}}
@once
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endonce

@php
    $id = $id ?? 'mapDisplay';
    $lat = $lat ?? null;
    $lng = $lng ?? null;
@endphp

@if($lat !== null && $lng !== null)
<div id="{{ $id }}" style="height: 300px; border-radius: 10px; border: 1px solid #d8dee6; z-index: 0;"></div>
<div class="mt-2 text-right">
    <a href="https://www.google.com/maps/search/?api=1&query={{ $lat }},{{ $lng }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger">
        <i class="fas fa-map-marker-alt"></i> Abrir con Google Maps
    </a>
</div>
<script>
(function () {
    function initDisplay() {
        var lat = {{ $lat }}, lng = {{ $lng }};
        var map = L.map('{{ $id }}').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap', maxZoom: 19
        }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        setTimeout(function () { map.invalidateSize(); }, 200);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDisplay);
    } else {
        initDisplay();
    }
})();
</script>
@else
<div class="alert alert-warning py-2 small mb-0">
    <i class="fas fa-map-marker-alt mr-1"></i> Sin ubicacion en el mapa.
</div>
@endif
