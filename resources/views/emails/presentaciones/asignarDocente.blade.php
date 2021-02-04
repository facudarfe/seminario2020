@extends('layouts.emails.inicio')

@section('contenido')
    <p>Se te ha asignado la corrección del siguiente seminario: </p>
    <p>
        <b>Alumno: </b> {{$presentacion->alumno->name}}<br>
        <b>Titulo: </b> {{$presentacion->titulo}}<br>
        <b>Director: </b> {{$presentacion->director->name}}<br>
        <b>Codirector: </b> {{$presentacion->codirector->name}}<br>
        <b>Modalidad: </b> {{$presentacion->modalidad->nombre}}
    </p>
    <p>Ingresa al sistema para ver el detalle de la presentación y realizar la corrección.</p>
@endsection