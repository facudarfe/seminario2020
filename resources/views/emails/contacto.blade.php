@extends('layouts.emails.inicio')

@section('contenido')
    <p>Ha recibido un nuevo mensaje a través del Formulario web de contacto:</p>
    <p>
        <b>Usuario: </b> {{$request->user()->name}} <br>
        <b>Rol: </b> {{$request->user()->getRoleNames()->first()}} <br>
        <b>Email: </b> {{$request->user()->email}} <br>
        <b>Asunto: </b> {{$request->asunto}} <br>
        <b>Descripción: </b>
        <p>{{$request->descripcion}}</p>
    </p>
@endsection