@extends('layouts.emails.inicio')

@section('contenido')
    <p>Se te informa que se te ha elegido para formar parte del tribunal evaluador {{$titular ? 'titular' : 'suplente'}} del siguiente 
    Seminario TUP:</p>
    <p>
        <b>Titulo del trabajo: </b> {{$anexo2->presentacion->titulo}}. <br>
        <b>Alumno/s: </b> <br>
        @foreach ($anexo2->presentacion->alumnos as $key => $alumno)
            @if ($key === count($anexo2->presentacion->alumnos)-1)
                {{$alumno->name}}
            @else
                {{$alumno->name . ' - '}}
            @endif
        @endforeach
        <br>
        <b>Modalidad: </b> {{$anexo2->presentacion->modalidad->nombre}} <br>
        <b>Director: </b> {{$anexo2->presentacion->director->name}} <br>
        <b>Codirector: </b> {{$anexo2->presentacion->codirector->name}} <br>
        <b>Fecha mesa examinadora: </b> {{$anexo2->fecha_definitiva}}
    </p>
    <p>Se adjunta formulario Anexo 2 e informe final del proyecto.</p>
@endsection
