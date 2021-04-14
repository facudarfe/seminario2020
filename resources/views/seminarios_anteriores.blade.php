<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Seminarios | Seminarios TUP</title>

    @include('includes.stylesheets')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

</head>


<header class="header">
    @include('includes.bars.home_topbar')
</header>
<body class="bg-gradient-primary">

    <div class="container-fluid">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-11">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <h3 class="mb-4">Seminarios años anteriores</h3>
                                    <div class="table-responsive">
                                        <table class="table" id="seminariosTable" width="100%" cellspacing="0">
                                            <thead>
                                                    <th>Fecha finalización</th>
                                                    <th>Titulo proyecto</th>
                                                    <th>Autor/es</th>
                                                    <th>Modalidad</th>
                                                    <th>Director</th>
                                                    <th>Codirector</th>
                                                    <th>Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($presentaciones as $presentacion)
                                                    <tr>
                                                        <td>{{explode(' ', $presentacion->anexos2[0]->fecha_definitiva)[0]}}</td>
                                                        <td>{{$presentacion->titulo}}</td>
                                                        <td>
                                                            @foreach ($presentacion->alumnos as $key => $alumno)
                                                                @if ($key === count($presentacion->alumnos)-1)
                                                                    {{$alumno->name}}
                                                                @else
                                                                    {{$alumno->name . ' - '}}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>{{$presentacion->modalidad->nombre}}</td>
                                                        <td>{{$presentacion->director->name}}</td>
                                                        <td>{{$presentacion->codirector->name}}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown no-arrow">
                                                                <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down"></i></a>
                                                                <div class="dropdown-menu shadow activeOptions">
                                                                    <a class="dropdown-item" href="{{route('anexos2.descargarInforme', $presentacion->anexos2[0])}}">
                                                                        <i class="fas fa-file-pdf fa-lg fa-fw text-gray-400"></i>
                                                                        Descargar informe final
                                                                    </a>
                                                                    @if ($presentacion->ruta_codigo)
                                                                        <a class="dropdown-item" href="{{route('presentaciones.descargarCodigoFuente', $presentacion)}}">
                                                                            <i class="fas fa-file-code fa-lg fa-fw text-gray-400"></i>
                                                                            Descargar codigo fuente
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
    
    <!-- Datatables-->
    <script src="{{asset('sbadmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('js/seminarios_anteriores.js')}}"></script>

</body>
<footer class="text-light p-2">
    <div class="container">
        <div class="copyright text-center">
            <small>{{$version}} - Desarrollado por Facundo Darfe</small>
        </div>
    </div>
</footer>

</html>