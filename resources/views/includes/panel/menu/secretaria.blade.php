@php $r = request(); @endphp

<li class="nav-item {{ $r->routeIs('home') || $r->is('*/home') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('home') || $r->is('*/home') ? 'active' : '' }}" href="{{ url('/home') }}">
        <i class="ni ni-tv-2"></i><strong>Dashboard</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('archivador.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('archivador.*') ? 'active' : '' }}" href="{{ route('archivador.index') }}">
        <i class="fas fa-archive"></i><strong>Inscripcion de Equipos</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('eventos.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('eventos.*') ? 'active' : '' }}" href="{{ route('eventos.index') }}">
        <i class="fas fa-calendar-alt"></i><strong>Configurar Eventos</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('fixture.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('fixture.*') ? 'active' : '' }}" href="{{ route('fixture.index') }}">
        <i class="fas fa-table"></i><strong>Fixture</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('disciplinas.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('disciplinas.*') ? 'active' : '' }}" href="{{ route('disciplinas.index') }}">
        <i class="fas fa-futbol"></i><strong>Disciplinas</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('carreras.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('carreras.*') ? 'active' : '' }}" href="{{ route('carreras.index') }}">
        <i class="fas fa-graduation-cap"></i><strong>Carreras</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('admin.lugares.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('admin.lugares.*') ? 'active' : '' }}" href="{{ route('admin.lugares.index') }}">
        <i class="fas fa-map-marker-alt"></i><strong>Lugares</strong>
    </a>
</li>
