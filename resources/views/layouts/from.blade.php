<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ config('app.name') }} | Iniciar Sesion</title>

  {{-- FOUC prevention: aplica el tema antes de cargar el CSS --}}
  <script>
    (function() {
      var t = localStorage.getItem('umsa-theme');
      if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    }());
  </script>

  <link href="{{ asset('img/brand/logos.jpg') }}" rel="icon" type="image/png">

  <link href="{{ asset('js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet">
  <link href="{{ asset('js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/argon-dashboard.css?v=1.1.2') }}" rel="stylesheet">
  <link href="{{ asset('css/umsa-theme.css') }}" rel="stylesheet">

  @yield('styles')
</head>
<body style="margin:0; padding:0; background:var(--umsa-bg, #F4F7F5);">

<div class="umsa-login-page">
  @yield('content')

  <p class="umsa-login-footer" style="margin-top:16px;">
    Universidad Mayor de San Andres &copy; {{ date('Y') }}
  </p>
</div>

<script src="{{ asset('js/plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/argon-dashboard.min.js?v=1.1.2') }}"></script>

@yield('scripts')

{{-- Boton flotante de tema (visible en login / register) --}}
<button class="umsa-float-theme-btn" id="floatThemeBtn" title="Cambiar tema" aria-label="Cambiar tema">
  <i class="fas fa-moon" id="floatThemeIcon"></i>
</button>
<script>
  (function () {
    var btn  = document.getElementById('floatThemeBtn');
    var icon = document.getElementById('floatThemeIcon');
    var html = document.documentElement;

    // Icono inicial
    icon.className = html.getAttribute('data-theme') === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

    btn.addEventListener('click', function () {
      var isDark = html.getAttribute('data-theme') === 'dark';
      if (isDark) {
        html.removeAttribute('data-theme');
        localStorage.setItem('umsa-theme', 'light');
        icon.className = 'fas fa-moon';
      } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('umsa-theme', 'dark');
        icon.className = 'fas fa-sun';
      }
    });
  }());
</script>
</body>
</html>
