@extends('layouts.emails.inicio')

@section('contenido')
    <p>Has solicitado una nueva mesa examinadora para tu trabajo final.</p>
    <p>
        <b>Fecha propuesta: </b> {{$anexo2->fecha_propuesta}}. <br>
        <b>Titulo del trabajo: </b> {{$anexo2->presentacion->titulo}}.
    </p>
    <p>Se te notificará cuando se evalúe tu solicitud y se establezca la fecha definitiva y tribunal evaluador.</p>
    <p>Se adjunta formulario Anexo 2.</p>
@endsection
