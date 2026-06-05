@extends('layouts.panel')

@section('title', 'Calendario - ' . $evento->nombre)

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
<style>
    #calendar { background: white; padding: 16px; border-radius: 12px; border: 1px solid #e5e7eb; }
    .fc-event { cursor: pointer; font-size: 11px; }
    .fc-toolbar-title { font-size: 1.1rem !important; }
    .partido-popup {
        display: none;
        position: fixed;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 12px;
        padding: 20px;
        width: 340px;
        z-index: 9999;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: 2px solid #dc2626;
    }
    .popup-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 9998;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
        <i class="fas fa-calendar-week text-danger"></i> Calendario - {{ $evento->nombre }}
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ url('/fixture/mis-fixtures') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>
</div>

<div id="calendar"></div>

{{-- Popup de detalle del partido --}}
<div class="popup-overlay" id="overlay" onclick="cerrarPopup()"></div>
<div class="partido-popup" id="popup">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 text-danger"><i class="fas fa-futbol"></i> Detalle del partido</h6>
        <button onclick="cerrarPopup()" class="btn-close"></button>
    </div>
    <p class="mb-1"><strong id="popup-serie"></strong></p>
    <div class="d-flex align-items-center justify-content-center gap-3 my-3">
        <span class="fw-bold" id="popup-local"></span>
        <span class="badge bg-danger px-3">VS</span>
        <span class="fw-bold" id="popup-visit"></span>
    </div>
    <div id="popup-marcador" class="text-center fs-4 fw-bold mb-2"></div>
    <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> <span id="popup-lugar"></span></p>
</div>

@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            list: 'Lista'
        },
        height: 'auto',
        events: '{{ route("fixture.calendario.json", $evento->id) }}',
        eventClick: function(info) {
            var p = info.event.extendedProps;
            document.getElementById('popup-serie').textContent = p.serie;
            document.getElementById('popup-local').textContent = p.local;
            document.getElementById('popup-visit').textContent = p.visit;
            document.getElementById('popup-lugar').textContent = p.lugar || 'Por definir';

            var gl = p.gl !== null && p.gl !== undefined ? p.gl : '-';
            var gv = p.gv !== null && p.gv !== undefined ? p.gv : '-';
            var marc = document.getElementById('popup-marcador');
            if (gl !== '-' || gv !== '-') {
                marc.textContent = gl + ' - ' + gv;
                marc.className = 'text-center fs-4 fw-bold mb-2 text-success';
            } else {
                marc.textContent = 'Por jugar';
                marc.className = 'text-center fs-6 fw-bold mb-2 text-muted';
            }

            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        },
    });
    calendar.render();
});

function cerrarPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
}
</script>
@endsection
