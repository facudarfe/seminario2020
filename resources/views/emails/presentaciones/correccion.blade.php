@extends('layouts.emails.inicio')

@section('contenido')
    <p>Hola {{$version->anexo->alumno->name}}, se ha realizado la corrección de tu ultima presentación.</p>
    <p>
        <b>Estado: </b> {{$version->estado->nombre}} <br>
        <b>Observaciones: </b> {{$version->observaciones}}
    </p>
    <div class="row">
        <div class="col-12 text-center">
            <a href="{{route('presentaciones.ver', $version->anexo)}}" class="btn btn-success">Ver presentación</a>
        </div>
    </div>
@endsection