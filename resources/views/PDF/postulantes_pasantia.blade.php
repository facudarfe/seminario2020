<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/pdf_presentacion.css')}}">
    <style>
        body{
            margin-top: 0cm;
            margin-bottom: 1cm;
        }
    </style>
    <title>Postulantes pasantía</title>
</head>
<body>
    <main>
        <div style="text-align: center; margin-bottom: 1cm;">
            <img src="{{asset('img/logo-v3.png')}}" alt="" style="max-width: 70px; vertical-align: middle;">
            <h3 style="display: inline; vertical-align: middle;">Postulantes pasantía - {{$pasantia->lugar}}</h3>
        </div>

        <table width="100%">
            <tr>
                <th>Nombre</th>
                <th>E-mail</th>
                <th>Teléfono</th>
                <th>Curriculum Vitae</th>
            </tr>
            @foreach ($pasantia->alumnos as $alumno)
                <tr class="text-center">
                    <td>{{$alumno->name}}</td>
                    <td>{{$alumno->email}}</td>
                    <td>{{$alumno->telefono}}</td>
                    <td>
                        <a href="{{Illuminate\Support\Facades\Storage::url($alumno->pivot->ruta_cv)}}" target="_blank">
                            <i class="fas fa-file-pdf mr-1"></i>Descargar CV
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </main>
    <footer>
        <small><i>Tecnicatura Universitaria en Programación - Seminario Técnico Profesional</i></small>
    </footer>
</body>
</html>