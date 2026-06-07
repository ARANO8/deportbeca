@extends('layouts.from')

@section('content')
<div class="umsa-login-card">

  {{-- Logo --}}
  <div class="umsa-login-logo">
    <i class="fas fa-shield-alt"></i>
  </div>
  <h1 class="umsa-login-title">DeportBeca</h1>
  <p class="umsa-login-subtitle">Sistema de Gestion Deportiva</p>

  {{-- Error --}}
  @if($errors->any())
    <div class="alert alert-danger mb-3" role="alert" style="font-size:13px;">
      <i class="fas fa-exclamation-circle mr-1"></i>
      Credenciales incorrectas. Verifique su correo y contrasena.
    </div>
  @endif

  <form role="form" method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Correo --}}
    <div class="mb-3">
      <label style="font-size:10.5px; font-weight:700; color:var(--umsa-text-muted); text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:5px;">
        Correo Electronico
      </label>
      <div class="umsa-login-input-wrap" style="position:relative;">
        <i class="fas fa-envelope login-icon" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--umsa-text-muted); font-size:13px; z-index:2;"></i>
        <input type="email" name="email" class="form-control"
               placeholder="usuario@umsa.bo"
               value="{{ old('email') }}"
               required autocomplete="email" autofocus
               style="padding-left:36px !important; height:42px !important;">
      </div>
    </div>

    {{-- Contrasena --}}
    <div class="mb-2">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
        <label style="font-size:10.5px; font-weight:700; color:var(--umsa-text-muted); text-transform:uppercase; letter-spacing:0.08em; margin:0;">
          Contrasena
        </label>
        <a href="{{ route('password.request') }}" class="umsa-login-forgot">
          ¿Olvidaste tu contrasena?
        </a>
      </div>
      <div style="position:relative;">
        <i class="fas fa-lock" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--umsa-text-muted); font-size:13px; z-index:2;"></i>
        <input type="password" name="password" id="loginPassword" class="form-control"
               placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
               required autocomplete="current-password"
               style="padding-left:36px !important; padding-right:42px !important; height:42px !important;">
        <button type="button" id="togglePassword" aria-label="Mostrar contrasena"
                style="position:absolute; right:6px; top:50%; transform:translateY(-50%); background:none; border:none; padding:6px 8px; cursor:pointer; color:var(--umsa-text-muted); z-index:3; line-height:1;">
          <i class="fas fa-eye" id="togglePasswordIcon" style="font-size:14px;"></i>
        </button>
      </div>
    </div>

    {{-- Submit --}}
    <div style="margin-top:20px;">
      <button type="submit" class="btn umsa-login-btn">
        Iniciar Sesion
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>
  </form>

</div>

<script>
(function () {
  var input = document.getElementById('loginPassword');
  var btn = document.getElementById('togglePassword');
  var icon = document.getElementById('togglePasswordIcon');
  if (!input || !btn || !icon) return;
  btn.addEventListener('click', function () {
    var oculto = input.type === 'password';
    input.type = oculto ? 'text' : 'password';
    icon.classList.toggle('fa-eye', !oculto);
    icon.classList.toggle('fa-eye-slash', oculto);
    btn.setAttribute('aria-label', oculto ? 'Ocultar contrasena' : 'Mostrar contrasena');
    input.focus();
  });
})();
</script>
@endsection
