@extends('layouts.emails.inicio')

@section('contenido')
    <p>Se ha definido la fecha y el tribunal evaluador para la presentación de tu proyecto.</p>
    <p>
        <b>Titulo del trabajo: </b> {{$anexo2->presentacion->titulo}}. <br>
        <b>Fecha definitiva mesa examinadora: </b> {{$anexo2->fecha_definitiva}} <br>
        <b>Tribunal titular: </b>
        @foreach ($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get() as $key => $docente)
            @if ($key === count($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get())-1)
                {{$docente->name}}
            @else
                {{$docente->name . ' - '}}
            @endif
        @endforeach
        <br>
        <b>Tribunal suplente: </b>
        @foreach ($anexo2->tribunal()->where('tribunales_evaluadores.titular', false)->get() as $key => $docente)
            @if ($key === count($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get())-1)
                {{$docente->name}}
            @else
                {{$docente->name . ' - '}}
            @endif
        @endforeach
    </p>
@endsection
