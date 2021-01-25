@extends('layouts.aplicacion')

@section('titulo', 'Crear usuario')

@section('metas')
    <meta name='csrf_token' content="{{csrf_token()}}">
@endsection
    
@section('titulo-contenido', 'Dar de alta a usuario')
    
@section('contenido')

    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    <form action="{{route('usuarios.almacenar')}}" method="POST" id="formUsuarios">
        @csrf
        <div class="row">
            <div class="col-xl-6 col-lg-8">
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="dni">*Documento: </label>
                        <input type="number" name="dni" class="form-control" value="{{old('dni')}}">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="lu">LU: </label>
                        <input type="number" name="lu" class="form-control" value="{{old('lu')}}">
                    </div>
                </div> 
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="name">*Nombre y Apellido: </label>
                        <input type="text" name="name" class="form-control" value="{{old('name')}}">
                    </div> 
                </div>
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="email">*Email: </label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="password">*Contraseña: </label>
                        <input type="password" name="password" class="form-control" value="{{old('password')}}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="rol">*Rol: </label>
                        <select name="rol" id="rol" class="custom-select">
                        @foreach ($roles as $rol)
                            @can('usuarios.crear.' . $rol->id)
                                <option selected value="{{$rol->id}}">{{$rol->name}}</option>
                            @endcan
                        @endforeach
                        </select>
                    </div>     
                </div>  
                <div class="form-row">
                    <div class="col-xl-6 form group">
                        <label for="direccion">Domicilio: </label>
                        <input type="text" name="direccion" class="form-control" value="{{old('direccion')}}">
                    </div>
                    <div class="col-xl-6 form group">
                        <label for="telefono">Telefono: </label>
                        <input type="number" name="telefono" class="form-control" value="{{old('telefono')}}">
                    </div>
                </div>
                <br>
                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <input type="submit" class="btn btn-block btn-success" value="Crear usuario">
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/validaciones/validacionUsuarios.js')}}"></script>
@endsection