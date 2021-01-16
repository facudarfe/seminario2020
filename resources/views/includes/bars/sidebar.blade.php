<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('inicio')}}">
        <div class="sidebar-brand-icon">
            <img src="{{asset('img/logo-v3.png')}}" alt="" class="img-fluid" style="max-width: 50px">
        </div>
        <div class="sidebar-brand-text mx-3">Seminarios TUP</div>
    </a>

    @canany (['usuarios.ver', 'permisos.ver', 'roles.ver'])
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Administración
    </div>

    <!-- Nav Item - Usuarios -->
    @can('usuarios.ver')
        <li class="nav-item">
            <a class="nav-link" href="{{route('usuarios.inicio')}}">
                <i class="fas fa-fw fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>
    @endcan
    
    <!-- Nav Item - Roles y permisos -->
    @can('permisos.ver')
        <li class="nav-item">
            <a class="nav-link" href="{{route('permisos.inicio')}}">
                <i class="fas fa-fw fa-key"></i>
                <span>Roles y Permisos</span>
            </a>
        </li>
    @endcan
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Seminario
    </div>

    <!-- Nav Item - Presentaciones -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('presentaciones.inicio')}}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Presentaciones</span>
        </a>
    </li>

    <!-- Nav Item - Propuestas temas -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-laptop"></i>
            <span>Propuestas temas</span>
        </a>
    </li>

    <!-- Nav Item - Propuestas pasantias -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Propuestas pasantias</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Contacto -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Contacto</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->