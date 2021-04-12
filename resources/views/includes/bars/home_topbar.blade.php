<nav class="navbar navbar-expand navbar-dark bg-transparent">        
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}"><i class="fas fa-sign-in-alt mr-1"></i>Login</a>
                </li>
            @endguest
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{route('inicio')}}"><i class="fas fa-home mr-1"></i>Inicio</a>
                </li>
            @endauth
            <li class="nav-item">
                <a class="nav-link" href="{{route('seminarios')}}"><i class="fas fa-clipboard-check mr-1"></i>Seminarios aprobados</a>
            </li>
        </ul>
    </div>
</nav>