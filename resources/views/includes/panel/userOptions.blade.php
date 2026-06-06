<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
    <div class="media-body ml-2 d-none d-lg-block">
      <span class="mb-0 text-sm font-weight-bold">Bienvenido: {{ auth()->user()->name }}</span>
    </div>

    <a href="{{ url('/NewPassword') }}" class="dropdown-item">
      <i class="ni ni-single-02"></i>
      <span>Mi perfil</span>
    </a>

    <div class="dropdown-divider"></div>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
      <i class="ni ni-user-run"></i>
      <span>Cerrar sesión</span>
    </a>
    <form action="{{ route('logout') }}" method="POST" style="display:none;" id="logout-form">
        @csrf
    </form>
</div>
