@extends('layouts.emails.inicio')

@section('contenido')
    <p>Hola {{$nombreUsuario}}, se le ha dado de alta en el sistema de Seminarios TUP: <a href="">{{route('inicio')}}</a></p>
    <p>Para ingresar al sitio debe utilizar su <b>DNI</b> como usuario y contraseña. Una vez que haya logrado ingresar no olvide
        modificar la contraseña por su seguridad.
    </p>
    @if ($rol == "Estudiante")
        <p>Recuerde subir la presentación correspondiente sobre su Seminario según las indicaciones dadas por los docentes
            de la cátedra.
        </p>
    @endif    
@endsection