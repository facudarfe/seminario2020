@extends('layouts.emails.inicio')

@section('contenido')
    <p>
        Hola 
        @foreach ($version->anexo->alumnos as $key => $alumno)
            @if (count($version->anexo->alumnos) == 1)
                {{$alumno->name}}
            @else
                @if ($key === count($version->anexo->alumnos)-1)
                    {{'y ' . $alumno->name}}
                @else
                    {{$alumno->name . ', '}}
                @endif
            @endif
        @endforeach
        , se ha realizado la corrección de tu ultima presentación.</p>
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