@php $r = request(); @endphp
<li class="nav-item {{ $r->routeIs('home') || $r->is('*/home') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('home') || $r->is('*/home') ? 'active' : '' }}" href="{{ url('/home') }}">
      <i class="ni ni-tv-2"></i><strong>Dashboard</strong>
    </a>
</li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/especialidades')}}">
      <i class="ni ni-briefcase-24 text-orange"></i><strong>Especialidades</strong>
    </a>
  </li>
  <li class="nav-item">
      <a class="nav-link  " href="{{url('/medicos')}}">
        <i class="fas fa-stethoscope text-info"></i><strong>Médicos</strong>
      </a>
    </li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/pacientes')}}">
      <i class="fas fa-bed text-red"></i><strong> Pacientes</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/historias')}}">
      <i class="ni ni-book-bookmark text-pink "></i><strong>Historial de pacientes</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/consultas')}}">
      <i class="fas fa-bullhorn text-success"></i><strong>Tipo consulta</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/reservarcitas/create')}}">
      <i class="ni ni-calendar-grid-58 text-primary"></i><strong>Reserva citas</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/miscitas')}}">
      <i class="fas fa-clock text-info"></i><strong>Citas médicas</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link " href="{{url('/paginawebs')}}">
      <i class="ni ni-ungroup text-danger"></i><strong>Pagina web</strong>
    </a>
  </li>

 