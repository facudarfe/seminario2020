@extends('layouts.emails.inicio')

@section('contenido')
    <p>Se ha creado satisfactoriamente su presentación con el nombre: <b>{{$titulo}}</b></p>
    <p>Se le enviará un correo electrónico cuando su presentación sea corregida.</p>
@endsection