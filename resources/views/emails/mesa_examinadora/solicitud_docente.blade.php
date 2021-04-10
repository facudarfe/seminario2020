@extends('layouts.emails.inicio')

@section('contenido')
    <p>Se ha solicitado mesa examinadora para el siguiente proyecto: </p>
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
        <b>Fecha propuesta: </b> {{$anexo2->fecha_propuesta}}.
    </p>
    <p>Ingresa al sistema para establecer la fecha definitiva y el tribunal evaluador</p>
    <p>Se adjunta formulario Anexo 2 e informe final.</p>
@endsection
