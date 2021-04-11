@extends('layouts.emails.inicio')

@section('contenido')
    <p>Se te ha asignado la corrección del siguiente seminario: </p>
    <p>
        <b>Alumno/s: </b> <br>
        @foreach ($presentacion->alumnos as $key => $alumno)
            @if ($key === count($presentacion->alumnos)-1)
                {{$alumno->name}}
            @else
                {{$alumno->name . ' - '}}
            @endif
        @endforeach
        <br>
        <b>Titulo: </b> {{$presentacion->titulo}}<br>
        <b>Director: </b> {{$presentacion->director->name}}<br>
        <b>Codirector: </b> {{$presentacion->codirector->name}}<br>
        <b>Modalidad: </b> {{$presentacion->modalidad->nombre}}
    </p>
    <p>Ingresa al sistema para ver el detalle de la presentación y realizar la corrección.</p>
@endsection