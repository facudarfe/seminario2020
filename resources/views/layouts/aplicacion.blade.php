<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name='csrf_token' content="{{csrf_token()}}">

    <title>@yield('titulo') | Seminarios TUP</title>

    @yield('otros-estilos')
    @include('includes.stylesheets')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!--Sidebar-->
        @include('includes.bars.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('includes.bars.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-3 text-gray-800">@yield('titulo-contenido')</h1>

                    @yield('contenido')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>{{\Tremby\LaravelGitVersion\GitVersionHelper::getNameAndVersion()}} - Desarrollado por Facundo Darfe</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cerrar sesión</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">¿Esta seguro que desea cerrar la sesión?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="{{route('logout')}}">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Perfil-->
    <div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mi Perfil</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body container">
                    <!--Fila exterior-->
                    <div class="row justify-content-center">
                        <div class="col-10 text-center">
                            <!--Filas interiores-->
                            <div class="row">
                                <h5 class="col-12">DNI</h5>
                                <p class="col-12">{{auth()->user()->dni}}</p>
                            </div>
                            @if (auth()->user()->getRoleNames()->first() == 'Estudiante')
                                <div class="row">
                                    <h5 class="col-12">LU</h5>
                                    <p class="col-12">{{auth()->user()->lu}}</p>
                                </div>
                            @endif
                            <div class="row">
                                <h5 class="col-12">Nombre</h5>
                                <p class="col-12">{{auth()->user()->name}}</p>
                            </div>
                            <div class="row">
                                <h5 class="col-12">Email</h5>
                                <p class="col-12">{{auth()->user()->email}}</p>
                            </div>
                            <div class="row">
                                <h5 class="col-12">Domicilio</h5>
                                <p class="col-12">{{auth()->user()->direccion}}</p>
                            </div>
                            <div class="row">
                                <h5 class="col-12">Teléfono</h5>
                                <p class="col-12">{{auth()->user()->telefono}}</p>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <hr>
                            <a href="{{route('usuarios.editarPerfil')}}"><i class="fas fa-pencil-alt"></i> Editar perfil </a> | 
                            <a href="#" id='changePassword'><i class="fas fa-key"></i> Cambiar contraseña</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal para cambiar la contraseña-->
    <div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body container">
                    <form action="{{route('usuarios.changePassword')}}" method="POST" id="formChangePassword">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-10">
                                <div class="form-row">
                                    <div class="col-12 form-group">
                                        <label for="dni">Contraseña actual: </label>
                                        <input type="password" name="oldpassword" id="oldpassword" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-12 form-group">
                                        <label for="dni">Contraseña nueva: </label>
                                        <input type="password" name="newpassword" id="newpassword" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-12 form-group">
                                        <label for="dni">Repetir contraseña nueva: </label>
                                        <input type="password" name="repeatnewpassword" id="repeatnewpassword" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-success" id="confirmChangePassword">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    @include('includes.scripts')
    @yield('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/aplicacion.js')}}"></script>

</body>

</html>