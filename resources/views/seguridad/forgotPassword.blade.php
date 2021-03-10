@extends('seguridad.layouts.app')

@section('titulo', 'Olvidé mi contraseña')

@section('contenido')
    <div class="text-center">
        <h1 class="h4 text-gray-900 my-4">Olvidé mi contraseña</h1>
    </div>

    <!--Mensajes del lado del servidor-->
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form class="user" method="POST" action="{{route('password.email')}}">
        @csrf
        <div class="form-group">
            <input type="email" class="form-control form-control-user"
                name="email" id="email" placeholder="Email">
        </div>
        <button type="submit" name="enviar" id="submit" class="btn btn-success btn-user btn-block">
            Enviar
        </button>
    </form>
@endsection