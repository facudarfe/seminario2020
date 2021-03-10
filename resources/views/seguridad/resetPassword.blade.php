@extends('seguridad.layouts.app')

@section('titulo', 'Resetear contraseña')
    
@section('contenido')
    <div class="text-center">
        <h1 class="h4 text-gray-900 my-4">Resetear contraseña</h1>
    </div>

    <!--Mensajes del lado del servidor-->
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form class="user" method="POST" action="{{route('password.update')}}">
        @csrf
        <input type="hidden" name="token" value="{{$token}}"">
        <div class="form-group">
            <input type="email" class="form-control form-control-user"
                name="email" id="email" placeholder="Email" value="{{old('email')}}">
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-user"
                name="password" id="password" placeholder="Contraseña">
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-user"
                name="password_confirmation" id="password_confirmation" placeholder="Repetir contraseña">
        </div>
        <button type="submit" name="enviar" id="submit" class="btn btn-success btn-user btn-block">
            Reestablecer contraseña
        </button>
    </form>
@endsection