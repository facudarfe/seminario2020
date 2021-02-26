@extends('layouts.aplicacion')

@section('titulo', 'Editando usuario')
    
@section('titulo-contenido', 'Modificando usuario')
    
@section('contenido')

    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    <form action="{{route('usuarios.actualizar', $user)}}" method="POST">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-xl-6 col-lg-8">
                <div class="form-row">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="col-xl-6 form-group">
                        <label for="dni">*Documento: </label>
                        <input type="number" name="dni" class="form-control" value="{{$user->dni}}">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="lu">LU: </label>
                        <input type="number" name="lu" class="form-control" value="{{old('lu', $user->lu)}}">
                    </div>
                </div> 
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="name">*Nombre y Apellido: </label>
                        <input type="text" name="name" class="form-control" value="{{old('name', $user->name)}}">
                    </div> 
                </div>
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="email">*Email: </label>
                        <input type="email" name="email" class="form-control" value="{{old('email', $user->email)}}">
                    </div>
                    @if ($user->hasRole('Administrador') && $admin)
                        <div class="col-xl-6 form-group">
                            <label for="password">*Contraseña: </label>
                            <input type="password" name="password" class="form-control">
                            <small>Deje este campo vacío si no desea modificar la contraseña.</small>
                        </div>   
                    @endif
                </div>
                @if ($admin) <!--Si se esta editando desde el panel admin se puede editar el rol-->
                    <div class="form-row">
                        <div class="col-xl-6 form-group">
                            <label for="rol">*Rol: </label>
                            <select name="rol" id="rol" class="custom-select">
                            @foreach ($roles as $rol)
                                <option {{$rol->id == $user->roles->first()->id ? 'selected' : ''}} 
                                    value="{{$rol->id}}">{{$rol->name}}</option>
                            @endforeach
                            </select>
                        </div>     
                    </div>                    
                @endif
                <div class="form-row">
                    <div class="col-xl-6 form group">
                        <label for="direccion">Domicilio: </label>
                        <input type="text" name="direccion" class="form-control" value="{{old('direccion', $user->direccion)}}">
                    </div>
                    <div class="col-xl-6 form group">
                        <label for="telefono">Telefono: </label>
                        <input type="number" name="telefono" class="form-control" value="{{old('telefono', $user->telefono)}}">
                    </div>
                </div>
                <br>
                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <input type="submit" class="btn btn-block btn-success" value="Modificar usuario">
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection