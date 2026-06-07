@php $r = request(); @endphp

<li class="nav-item {{ $r->routeIs('home') || $r->is('*/home') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('home') || $r->is('*/home') ? 'active' : '' }}" href="{{ url('/home') }}">
        <i class="ni ni-tv-2"></i><strong>Dashboard</strong>
    </a>
</li>

<li class="nav-item {{ $r->is('*/users*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->is('*/users*') ? 'active' : '' }}" href="{{ url('/users') }}">
        <i class="ni ni-single-02"></i><strong>Usuarios</strong>
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

<li class="nav-item {{ $r->routeIs('eventos.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('eventos.*') ? 'active' : '' }}" href="{{ route('eventos.index') }}">
        <i class="fas fa-calendar-alt"></i><strong>Eventos</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('archivador.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('archivador.*') ? 'active' : '' }}" href="{{ route('archivador.index') }}">
        <i class="fas fa-archive"></i><strong>Inscripcion de Equipos</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('admin.lugares.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('admin.lugares.*') ? 'active' : '' }}" href="{{ route('admin.lugares.index') }}">
        <i class="fas fa-map-marker-alt"></i><strong>Lugares</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('fixture.*') && !$r->routeIs('fixture.mis.*') && !$r->routeIs('fixture.calendario') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('fixture.*') && !$r->routeIs('fixture.mis.*') && !$r->routeIs('fixture.calendario') ? 'active' : '' }}" href="{{ route('fixture.index') }}">
        <i class="fas fa-table"></i><strong>Fixture</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('fixture.mis.*') || $r->routeIs('fixture.calendario') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('fixture.mis.*') || $r->routeIs('fixture.calendario') ? 'active' : '' }}" href="{{ route('fixture.mis.fixtures') }}">
        <i class="fas fa-layer-group"></i><strong>Mis Fixture</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('privilegios.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('privilegios.*') ? 'active' : '' }}" href="{{ route('privilegios.index') }}">
        <i class="fas fa-lock"></i><strong>Privilegios</strong>
    </a>
</li>

<li class="nav-item {{ $r->routeIs('roles.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
        <i class="fas fa-tag"></i><strong>Roles</strong>
    </a>
</li>

<li class="nav-item {{ $r->is('*/paginawebs*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->is('*/paginawebs*') ? 'active' : '' }}" href="{{ url('/paginawebs') }}">
        <i class="ni ni-ungroup"></i><strong>panel comunicados</strong>
    </a>
</li>
