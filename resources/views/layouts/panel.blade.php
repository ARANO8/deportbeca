<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }} | Panel de Control</title>

  {{-- FOUC prevention: aplica el tema guardado antes de que cargue el CSS --}}
  <script>
    (function() {
      var t = localStorage.getItem('umsa-theme');
      if (t === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
      }
    }());
  </script>

  <link href="{{ asset('img/brand/logos.jpg') }}" rel="icon" type="image/png">

  {{-- Nucleo + FontAwesome --}}
  <link href="{{ asset('js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet">
  <link href="{{ asset('js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

  {{-- Argon base (Bootstrap 4 + componentes) --}}
  <link href="{{ asset('css/argon-dashboard.css?v=1.1.29') }}" rel="stylesheet">

  {{-- Toastr --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  {{-- Tema UMSA (override argon) --}}
  <link href="{{ asset('css/umsa-theme.css') }}" rel="stylesheet">

  @yield('styles')
</head>

<body>
<div class="umsa-wrapper">

  {{-- ================================================================
       SIDEBAR
       ================================================================ --}}
  <aside class="umsa-sidebar" id="umsaSidebar">

    {{-- Brand --}}
    <a href="{{ url('/home') }}" class="umsa-sidebar-brand">
      <div class="umsa-sidebar-brand-icon">
        <i class="fas fa-shield-alt"></i>
      </div>
      <div>
        <span class="umsa-sidebar-brand-title">DeportBeca</span>
        <span class="umsa-sidebar-brand-sub">Gestion Deportiva</span>
      </div>
    </a>

    {{-- Menu principal del rol --}}
    <div class="umsa-sidebar-body">
      <p class="umsa-nav-section">
        @if(auth()->user()->role === 'admin') Gestion @else Menu @endif
      </p>
      <ul class="umsa-sidebar-nav">
        @include('includes.panel.menu.' . auth()->user()->role)
      </ul>
    </div>

    {{-- Footer del sidebar --}}
    <div class="umsa-sidebar-footer">
      <ul class="umsa-sidebar-nav">
        <li class="nav-item">
          <a href="{{ route('alertas.index') }}" class="nav-link">
            <i class="fas fa-bell"></i>
            <strong>Alertas</strong>
            @php $noLeidas = auth()->user()?->alertasNoLeidas()->count() ?? 0; @endphp
            @if($noLeidas > 0)
              <span class="umsa-nav-badge">{{ $noLeidas > 9 ? '9+' : $noLeidas }}</span>
            @endif
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('perfil.editar') }}" class="nav-link">
            <i class="fas fa-user-circle"></i>
            <strong>Mi Perfil</strong>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link"
             onclick="event.preventDefault(); document.getElementById('umsa-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <strong>Cerrar Sesion</strong>
          </a>
          <form id="umsa-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </li>
      </ul>
    </div>

  </aside>
  {{-- /sidebar --}}

  {{-- ================================================================
       MAIN
       ================================================================ --}}
  <div class="umsa-main">

    {{-- TOPBAR --}}
    <header class="umsa-topbar">
      {{-- Mobile hamburger --}}
      <button class="umsa-icon-btn d-md-none" id="sidebarToggle" aria-label="Menu"
              onclick="document.getElementById('umsaSidebar').classList.toggle('sidebar-open')">
        <i class="fas fa-bars"></i>
      </button>

      <div class="umsa-topbar-title">
        {{ config('app.name') }}
        <small>&rsaquo; Panel de Control</small>
      </div>

      <div class="umsa-topbar-actions">
        {{-- Toggle de tema --}}
        <button class="umsa-icon-btn" id="themeToggleBtn" title="Cambiar tema" aria-label="Cambiar tema">
          <i class="fas fa-moon" id="themeIcon"></i>
        </button>

        {{-- Notificaciones --}}
        @php $noLeidas = auth()->user()?->alertasNoLeidas()->count() ?? 0; @endphp
        <a href="{{ route('alertas.index') }}" class="umsa-icon-btn" title="Alertas">
          <i class="fas fa-bell"></i>
          @if($noLeidas > 0)
            <span class="umsa-badge-dot">{{ $noLeidas > 9 ? '9+' : $noLeidas }}</span>
          @endif
        </a>

        {{-- Usuario --}}
        <div class="dropdown">
          <a href="#" class="umsa-user-chip" data-toggle="dropdown"
             aria-haspopup="true" aria-expanded="false" id="topbarUserMenu">
            <div class="umsa-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="d-none d-md-block">
              <div class="umsa-user-name">{{ auth()->user()->name }}</div>
              <div class="umsa-user-role">{{ ucfirst(auth()->user()->role ?? 'Usuario') }}</div>
            </div>
            <i class="fas fa-chevron-down ml-1" style="font-size:9px; color:var(--umsa-text-muted);"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topbarUserMenu">
            <a class="dropdown-item" href="{{ route('perfil.editar') }}">
              <i class="fas fa-user-edit mr-2"></i> Mi Perfil
            </a>
            <a class="dropdown-item" href="{{ route('NewPassword') }}">
              <i class="fas fa-key mr-2"></i> Cambiar Contrasena
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('umsa-logout-form').submit();">
              <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesion
            </a>
          </div>
        </div>
      </div>
    </header>
    {{-- /topbar --}}

    {{-- CONTENT --}}
    <main class="umsa-content">
      @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="umsa-footer">
      <span>&copy; {{ date('Y') }} UMSA &mdash; {{ config('app.name') }}</span>
      <span>"El deporte abre puertas"</span>
    </footer>

  </div>
  {{-- /main --}}

</div>
{{-- /wrapper --}}

{{-- Overlay mobile sidebar --}}
<div id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:999;"
     onclick="document.getElementById('umsaSidebar').classList.remove('sidebar-open'); this.style.display='none';"></div>

{{-- Core JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('js/argon-dashboard.min.js?v=1.1.2') }}"></script>
<script src="{{ asset('js/t.js') }}"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
  pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

{{-- Toastr config --}}
<script>
  toastr.options = {
    closeButton:      true,
    newestOnTop:      true,
    progressBar:      true,
    positionClass:    'toast-top-right',
    preventDuplicates: true,
    timeOut:          5000
  };
</script>

{{-- Mobile sidebar toggle --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('sidebarToggle');
    var overlay = document.getElementById('sidebarOverlay');
    var sidebar = document.getElementById('umsaSidebar');
    if (toggle) {
      toggle.addEventListener('click', function () {
        var open = sidebar.classList.contains('sidebar-open');
        overlay.style.display = open ? 'none' : 'block';
      });
    }
  });
</script>

{{-- Tema claro / oscuro --}}
<script>
  (function () {
    var btn  = document.getElementById('themeToggleBtn');
    var icon = document.getElementById('themeIcon');
    var html = document.documentElement;

    function applyTheme(theme) {
      if (theme === 'dark') {
        html.setAttribute('data-theme', 'dark');
        icon.classList.replace('fa-moon', 'fa-sun');
        btn.title = 'Cambiar a modo claro';
      } else {
        html.removeAttribute('data-theme');
        icon.classList.replace('fa-sun', 'fa-moon');
        btn.title = 'Cambiar a modo oscuro';
      }
    }

    // Inicializar icono segun tema actual (ya aplicado por el script inline del head)
    var saved = localStorage.getItem('umsa-theme');
    applyTheme(saved === 'dark' ? 'dark' : 'light');

    if (btn) {
      btn.addEventListener('click', function () {
        var current = html.getAttribute('data-theme');
        var next = current === 'dark' ? 'light' : 'dark';
        localStorage.setItem('umsa-theme', next);
        applyTheme(next);
      });
    }
  }());
</script>

@yield('scripts')
</body>
</html>
