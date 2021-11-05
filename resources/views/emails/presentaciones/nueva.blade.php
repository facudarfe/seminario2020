@extends('layouts.emails.inicio')

@section('contenido')
    @if ($rol == 'Estudiante')
        <p>Se ha creado satisfactoriamente su presentación con el nombre: <b>{{$titulo}}</b></p>
        <p>Se le enviará un correo electrónico cuando su presentación sea corregida.</p>
    @else
        <p>El/La alumno/a {{$alumno}} ha cargado una nueva presentación en el sistema.</p>
        <p>El titulo del trabajo es: <b>{{$titulo}}</b></p><br>
        <p>Dicha presentación se mantendra en estado <i>Pendiente</i> hasta que se le asigne el Docente evaluador para que lo corrija.</p>
    @endif
@endsection