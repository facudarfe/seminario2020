    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="{{asset('css/pdf_presentacion.css')}}">
        <title>Presentación</title>
    </head>
    <body>
        <header>
            <img src="{{asset('img/header-unsa.png')}}" alt="" style="width: 50%; height: auto;">
        </header>
        <main>
            <p class="fecha">{{$fecha}}</p>
            <p class="saludo"><b>
                A la Comisión de Seminario de <br>
                Tecnicatura Universitaria en Programación</b>
            </p>
            <p class="nota">
                Me dirijo a Uds. a los efectos de poner a su consideración el tema <b>“{{$presentacion->titulo}}”</b>, 
                para el trabajo de la asignatura Seminario Técnico Profesional de la Tecnicatura Universitaria en Programación. 
                Adjunto a la misma el Formulario Anexo 1 de Presentación de Tema.
            </p>
            <p>
                Sin otro particular, saludo a Uds. muy atentamente.
            </p>
            <table width="100%" style="margin-top: 5em;">
                    @foreach ($presentacion->alumnos as $alumno)
                        <tr class="text-right">
                            <td colspan="2">
                                <p>______________________</p>
                                <p>
                                    Alumno: {{$alumno->name}}<br>
                                    LU: {{$alumno->lu}}
                                </p> 
                            </td>
                        </tr>
                    @endforeach          
                <tr>
                    <td class="separacion-firma">
                        <p>______________________</p>
                        <p>
                            Director: {{$presentacion->director->name}}
                        </p>
                    </td>
                    @if ($presentacion->codirector)
                        <td class="text-right separacion-firma">
                            <p>______________________</p>
                            <p>
                                Codirector: {{$presentacion->codirector->name}}
                            </p>
                        </td>
                    @endif  
                </tr>
            </table>

            <!--Nueva pagina-->
            <div class="page-break"></div>
            <p><b>ANEXO 1: FORMULARIO 1</b></p>
            <table class="tabla">
                <tr>
                    <td><b>Trabajo</b></td> 
                    <td colspan="2">{{$presentacion->titulo}}</td>
                </tr>
                <tr>
                    <td><b>Director</b></td>
                    <td colspan="2">{{$presentacion->director->name}}</td>
                </tr>
                <tr>
                    <td><b>Co-director</b></td>
                    <td colspan="2">{{$presentacion->codirector ? $presentacion->codirector->name : '-'}}</td>
                </tr>
                <tr class="text-center">
                    <td colspan="3"><b>Alumno/s</b></td>
                </tr>
                @foreach ($presentacion->alumnos as $alumno)
                    <tr class="text-center">
                        <td><b>LU</b></td>
                        <td><b>Apellidos y Nombres</b></td>
                        <td><b>Dirección, teléfono, e-mail</b></td>
                    </tr>
                    <tr class="text-center">
                        <td>{{$alumno->lu}}</td>
                        <td>{{$alumno->name}}</td>
                        <td>{{$alumno->direccion . ' - ' . $alumno->telefono . ' - ' . $alumno->email}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td><b>Modalidad</b></td>
                    <td colspan="2">{{$presentacion->modalidad->nombre}}</td>
                </tr>
                <tr class="text-center">
                    <td colspan="3"><b>Resumen del trabajo temático</b></td>
                </tr>
                <tr>
                    <td colspan="3">
                        {!! nl2br($version->resumen) !!}
                    </td>
                </tr>
                <tr class="text-center">
                    <td colspan="3"><b>Descripción detallada del proyecto</b></td>
                </tr>
            </table>
            <div id="descripcion">
                {!! nl2br($version->descripcion) !!}
                <p><b>Tecnologias utilizadas: </b></p>
                <p>{!! nl2br($version->tecnologias) !!}</p>
            </div>
            <br> <br> <br> <br> <br> <br>
            <hr class="firma">
            <table width="100%">
                <tr class="text-center">
                    @foreach ($presentacion->alumnos as $alumno)
                        <td>Alumno: {{$alumno->name}}</td>
                    @endforeach
                    <td>Director: {{$presentacion->director->name}}</td>
                    @if ($presentacion->codirector)
                        <td>Codirector: {{$presentacion->codirector->name}}</td>
                    @endif
                </tr>
                <tr class="text-center">
                    <td colspan="4"><small><i>Los firmantes declaran conocer y aceptar el reglamento de la Cátedra de Seminario Técnico Profesional</i></small></td>
                </tr>
            </table>

            <!--Nueva pagina-->
            <div class="page-break"></div>
            <div class="bordes" style="padding: 0.2cm">
                <p><b>Evaluacion de Presentación de Tema</b></p>
                <table width="100%">
                    <tr>
                        <td>Aceptado <div class="{{$version->estado->nombre == 'Aceptado' ? 'checkbox-filled' : 'checkbox'}}"></div></td>
                        <td>Reformular <div class="{{$version->estado->nombre == 'Resubir' ? 'checkbox-filled' : 'checkbox'}}"></div></td>
                        <td>Rechazado <div class="{{$version->estado->nombre == 'Rechazado' ? 'checkbox-filled' : 'checkbox'}}"></div></td>
                    </tr>
                </table>
                <p>Observaciones:</p>
                @if ($version->observaciones)
                    <p>{{$version->observaciones}}</p>
                @else
                    <hr class="separacion">
                    <hr class="separacion">
                    <hr class="separacion">
                    <hr class="separacion">
                    <hr class="separacion">
                @endif

                <p style="margin-bottom: 7cm;"><b>Miembros Comisión de Seminario TUP:</b></p>
            </div>
        </main>
        <footer>
            <small><i>Tecnicatura Universitaria en Programación - Seminario Técnico Profesional</i></small>
        </footer>
    </body>
    </html>