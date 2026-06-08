{{--
    Selector de ubicacion con Leaflet + OpenStreetMap (sin API key).
    Uso: @include('partials.map-picker', ['id'=>'mapLugar','latField'=>'latitud','lngField'=>'longitud','lat'=>$lugar->latitud ?? null,'lng'=>$lugar->longitud ?? null])
--}}
@once
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endonce

@php
    $id = $id ?? 'mapPicker';
    $latField = $latField ?? 'latitud';
    $lngField = $lngField ?? 'longitud';
    $lat = $lat ?? null;
    $lng = $lng ?? null;
@endphp

<div class="mb-2">
    <div class="input-group">
        <input type="text" id="{{ $id }}_search" class="form-control" placeholder="Buscar direccion o lugar (ej: Av. Villazon, La Paz)" autocomplete="off">
        <div class="input-group-append">
            <button type="button" id="{{ $id }}_searchBtn" class="btn btn-secondary"><i class="fas fa-search"></i> Buscar</button>
        </div>
    </div>
    <small class="text-muted">Busca una direccion, o haz clic en el mapa / arrastra el marcador para fijar la ubicacion.</small>
</div>

<div id="{{ $id }}" style="height: 320px; border-radius: 10px; border: 1px solid #d8dee6; z-index: 0;"></div>

<input type="hidden" name="{{ $latField }}" id="{{ $id }}_lat" value="{{ old($latField, $lat) }}">
<input type="hidden" name="{{ $lngField }}" id="{{ $id }}_lng" value="{{ old($lngField, $lng) }}">

<script>
(function () {
    function initPicker() {
        var defaultLat = -16.5000, defaultLng = -68.1193; // La Paz, Bolivia
        var latInput = document.getElementById('{{ $id }}_lat');
        var lngInput = document.getElementById('{{ $id }}_lng');
        var hasCoords = latInput.value !== '' && lngInput.value !== '';
        var startLat = hasCoords ? parseFloat(latInput.value) : defaultLat;
        var startLng = hasCoords ? parseFloat(lngInput.value) : defaultLng;

        var map = L.map('{{ $id }}').setView([startLat, startLng], hasCoords ? 16 : 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap', maxZoom: 19
        }).addTo(map);

        var marker = null;
        function setMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function (e) {
                    var p = e.target.getLatLng();
                    latInput.value = p.lat.toFixed(7);
                    lngInput.value = p.lng.toFixed(7);
                });
            }
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
        }
        if (hasCoords) { setMarker(startLat, startLng); }

        map.on('click', function (e) { setMarker(e.latlng.lat, e.latlng.lng); });

        function buscar() {
            var q = document.getElementById('{{ $id }}_search').value.trim();
            if (!q) { return; }
            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.length) {
                    var lat = parseFloat(data[0].lat), lng = parseFloat(data[0].lon);
                    map.setView([lat, lng], 16);
                    setMarker(lat, lng);
                } else if (typeof toastr !== 'undefined') {
                    toastr.warning('No se encontro la direccion.');
                }
            })
            .catch(function () {
                if (typeof toastr !== 'undefined') { toastr.error('Error al buscar la direccion.'); }
            });
        }
        document.getElementById('{{ $id }}_searchBtn').addEventListener('click', buscar);
        document.getElementById('{{ $id }}_search').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); buscar(); }
        });

        setTimeout(function () { map.invalidateSize(); }, 200);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPicker);
    } else {
        initPicker();
    }
})();
</script>
