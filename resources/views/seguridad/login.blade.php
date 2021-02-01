<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login | Seminarios TUP</title>

    @include('includes.stylesheets')


</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <!--<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>-->
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img class="img-fluid" src="{{asset('img/logo-v2.jpg')}}" alt="" style="width: 100px; height: 100px;">
                                        <h3 class="d-sm-inline align-middle ml-2">Seminarios TUP</h3>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 my-4">Inicia sesión</h1>
                                    </div>

                                    <!--Mensajes de error del lado del servidor-->
                                    @include('includes.mensajes_error')

                                    <form class="user" method="POST" action="{{route('login-post')}}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-user"
                                                name="dni" id="dni" aria-describedby="dniHelp"
                                                placeholder="Documento">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" id="password" placeholder="Contraseña">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                                                <label class="custom-control-label" for="remember">Recordarme</label>
                                            </div>
                                        </div>
                                        <button type="submit" name="enviar" id="submit" class="btn btn-success btn-user btn-block">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Olvidé mi contraseña</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    @include('includes.scripts')

</body>

</html>