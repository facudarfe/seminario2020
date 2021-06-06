<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/pdf_presentacion.css')}}">
    <style>
        body{
            font-size: 0.85em;
        }

        tr{
            line-height: 1.5cm;
            min-height: 1.5cm;
            height: 1.5cm;
        }

        hr.separacion{
            margin-top: 0.70cm;
            margin-bottom: 0.75cm;
        }
    </style>
    <title>Anexo 2</title>
</head>
<body>
    <header>
        <img src="{{asset('img/header-unsa.png')}}" alt="" style="width: 50%; height: auto;">
    </header>
    <main>
        <p class="text-center">
            <b>ANEXO II<br>
            Formulario 2</b>
        </p>
        <p class="text-center">
            Solicitud de constitución de Mesa Examinadora para la asignatura <br><b>SEMINARIO TÉCNICO PROFESIONAL</b><br> 
            de la carrera Tecnicatura universitaria en Programación - Plan 2012
        </p>
        <br>

        <p class="text-right">Salta, {{date('d/m/Y', strtotime(now()))}}</p>
        <table>
            <tr>
                <td><b>Alumno(s)</b></td>
                <td>
                    @foreach ($presentacion->alumnos as $alumno)
                        {{$alumno->name}}
                        <br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><b>Director(es)</b></td>
                <td>{{$presentacion->director->name}}</td>
            </tr>
            <tr>
                <td><b>Codirector(es)</b></td>
                <td>{{$presentacion->codirector ? $presentacion->codirector->name : '-'}}</td>
            </tr>
            <tr>
                <td><b>Titulo del trabajo</b></td>
                <td>{{$presentacion->titulo}}</td>
            </tr>
            <tr>
                <td><b>Fecha del examen final</b></td>
                <td>{{date('d/m/Y', strtotime($anexo2->getRawOriginal('fecha_propuesta')))}}. Hora: {{date('H:i', strtotime($anexo2->getRawOriginal('fecha_propuesta')))}}</td>
            </tr>
        </table>
        <p>Se adjuntan a la presente 4 (cuatro) ejemplares del Trabajo Final, debidamente firmados 
            por alumno(s), director(es) y co-director(es).</p>

        <hr class="firma" style="margin-top: 2.5cm;">
        <p class="text-center">Firma del (de los) alumno(s)</p>

        <hr class="firma" style="margin-top: 2.5cm;">
        <p class="text-center">Firma del (de los) director(es) y co-director(es)</p>

        <!--Nueva pagina-->
        <div class="page-break"></div>
        <p><b>Departamento de alumnos, </b>___ / ___ / ___ .</p>
        <p>Se informa que los alumnos:</p>
        <hr class="separacion">
        <hr class="separacion">
        <p class="justify-content">se encuentran en condiciones reglamentarias de rendir la asignatura SEMINARIO TÉCNICO PROFESIONAL, del Plan 2012 de la
            Tecnicatura Universitaria en Programación
        </p>
        <div class="text-right">
            <p>_________________________</p>
            <p>Jefe del Departamento de Alumnos</p>
        </div>
        <hr>
        <p><b>Comisión de Seminario TUP, </b>{{$anexo2->fecha_definitiva ? date('d/m/Y', strtotime(now())) : '___ / ___ / ___ .'}}</p>
        <p>Propuesta de Tribunal examinador:</p>
        <p>Miembros titulares</p>
        @if ($anexo2->fecha_definitiva)
            @foreach ($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get() as $docente)
                {{$docente->name}}
                <br>
            @endforeach
        @else
            <hr class="separacion">
            <hr class="separacion">
        @endif
        <p>Miembros suplentes</p>
        @if ($anexo2->fecha_definitiva)
            @foreach ($anexo2->tribunal()->where('tribunales_evaluadores.titular', false)->get() as $docente)
                {{$docente->name}}
                <br>
            @endforeach
        @else
            <hr class="separacion">
            <hr class="separacion">
        @endif

        <p>Constitución del tribunal examinador:</p>
        <p>{{$anexo2->fecha_definitiva ? 'Fecha: ' . date('d/m/Y', strtotime($anexo2->getRawOriginal('fecha_definitiva'))) . '. Horas: ' . date('H:i', strtotime($anexo2->getRawOriginal('fecha_definitiva'))) : 'Fecha: __/__/__. Horas: ____'}}</p>
        <div class="text-right">
            <p>_________________________</p>
        </div>
        <p>P/Comisión de Seminario de Computación</p>
        <hr>

        <p><b>Departamento de Informática, </b>___ / ___ / ___ .</p>
        <p class="justify-content">Este Departamento ________________ (avala – no avala) la propuesta de Tribunal Examinador 
            y fecha y hora de constitución efectuada por la Comisión de Seminario TUP.</p>
        <div class="text-right">
            <p>_________________________</p>
            <p style="font-size: 0.85em;">Director del Departamento de Informática</p>
        </div>

        <!--Nueva pagina-->
        <div class="page-break"></div>
        <p><b>Facultad de Ciencias Exactas, </b>___ / ___ / ___ .</p>
        <p class="text-center"><b>PROVIDENCIA RESOLUTIVA</b></p>
        <p>Visto el pedido de constitución de Mesa Examinadora para la asinatura Seminario Técnico Profesional, 
            efectuado por el(los) alumno(s)</p>
        <hr class="separacion">
        <hr class="separacion">   
        <p class="justify-content">que Departamento Alumnos informa que se encuentran en condiciones reglamentarias para rendir tal asignatura 
            y que el Departamento de Informática ha avalado la sugerencia de miembros docentes para integrar el Tribunal Examinador, 
            efectuada por la Comisión de Seminario TUP, </p> 
            <p class="text-center"><b>EL DECANO DE LA FACULTAD DE CIENCIAS EXACTAS RESUELVE: </b></p>
            <p class="justify-content">
                <u>Artículo 1º:</u> Autorizar la constutución del Tribunal Examinador para la Mesa Examinadora para la asignatura 
                Seminario Técnico Profesional, a constituirse el día _____ / _____ /_____ , a las ___________ horas.
            </p>
            <p class="justify-content">
                <u>Artículo 2º:</u> Notificar al Tribunal Examinador de la presente providencia resolutiva.
            </p>
            <p class="justify-content">
                <u>Artículo 3º:</u> Hágase saber al(a los) alumno(s), al Departamento de Alumnos y archívese.
            </p>

    </main>
    <footer>
        <small><i>Tecnicatura Universitaria en Programación - Seminario Técnico Profesional</i></small>
    </footer>
</body>
</html>