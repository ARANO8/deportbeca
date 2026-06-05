<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ config('app.name') }} | Iniciar Sesion</title>

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
</body>
</html>
