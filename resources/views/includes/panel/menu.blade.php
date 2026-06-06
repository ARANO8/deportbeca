
<h6 class="navbar-heading text-muted">
  @if(auth()->user()->esSuperAdmin())
    Gestión
  @else
     Menú :
  @endif
</h6>

<ul class="navbar-nav">
  @include('includes.panel.menu.'.auth()->user()->menuKey())
    <li class="nav-item">
      <a href="{{ route('alertas.index') }}" class="nav-link position-relative" title="Alertas">
        <i class="fas fa-bell"></i>
        @php
            $noLeidas = auth()->user()?->alertasNoLeidas()->count() ?? 0;
        @endphp
        @if($noLeidas > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
            {{ $noLeidas > 9 ? '9+' : $noLeidas }}
        </span>
        @endif
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link nav-link-icon" href="{{ route('logout') }}" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-in-alt"></i><strong>cerrar sesion</strong>
      </a>
      <form action="{{route('logout')}}" method="POST" style="display:none;"id="logout-form">
        @csrf
    </form>
    </li>

   
    
    
    
  </ul>
  <!-- @if(auth()->user()->role=='admin')
  <hr class="my-3">
  <h6 class="navbar-heading text-muted">Reportes</h6>
  <ul class="navbar-nav mb-md-3">
    <li class="nav-item">
      <a class="nav-link" href="{{url('/reportes/citas/line')}}">
        <i class="ni ni-books text-default"></i><strong>Citas Medica</strong>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{url('/reportes/doctors/column')}}">
        <i class="ni ni-chart-bar-32 text-Warning"></i><strong> Desempeño Medico</strong>
      </a>
    </li>
  </ul>
  @endif -->
  

 
