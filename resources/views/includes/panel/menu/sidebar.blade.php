@php
$menuKey = auth()->user()->menuKey();
@endphp

<li class="nav-item">
    <a class="nav-link" href="{{ route('home') }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('usuarios', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class="fas fa-users"></i>
        <span>Usuarios</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('disciplinas', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('disciplinas.index') }}">
        <i class="fas fa-futbol"></i>
        <span>Disciplinas</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('carreras', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('carreras.index') }}">
        <i class="fas fa-graduation-cap"></i>
        <span>Carreras</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('eventos', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('eventos.index') }}">
        <i class="fas fa-calendar-alt"></i>
        <span>Eventos</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('preinscripciones', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('archivador.index') }}">
        <i class="fas fa-clipboard-list"></i>
        <span>Inscripción de Equipos</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('lugares', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.lugares.index') }}">
        <i class="fas fa-map-marker-alt"></i>
        <span>Lugares</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('fixture', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('fixture.index') }}">
        <i class="fas fa-chart-line"></i>
        <span>Fixture</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('fixture', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('fixture.mis.fixtures') }}">
        <i class="fas fa-calendar-week"></i>
        <span>Mis Fixture</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('roles', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('roles.index') }}">
        <i class="fas fa-user-shield"></i>
        <span>Roles</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('privilegios', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('privilegios.index') }}">
        <i class="fas fa-lock"></i>
        <span>Privilegios</span>
    </a>
</li>
@endif

@if(auth()->user()->esSuperAdmin() || auth()->user()->rol?->tienePermiso('comunicados', 'ver'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('paginawebs.index') }}">
        <i class="fas fa-newspaper"></i>
        <span>Panel Comunicados</span>
    </a>
</li>
@endif

<hr class="my-3">

<li class="nav-item">
    <a class="nav-link" href="#" id="darkModeToggle">
        <i class="fas fa-moon"></i>
        <span>Modo oscuro</span>
    </a>
</li>