@extends('layouts.aplicacion')

@section('titulo', 'Crear docente')
    
@section('titulo-contenido', 'Crear nuevo docente')
    
@section('contenido')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    <form action="{{route('docentes.almacenar')}}" method="POST" id="formDocentes">
        @csrf
        <div class="row">
            <div class="col-xl-6 col-lg-8">
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="dni">*Documento: </label>
                        <input type="number" name="dni" class="form-control" value="{{old('dni', $docente->dni)}}">
                    </div>
                </div> 
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="name">*Nombre y Apellido: </label>
                        <input type="text" name="name" class="form-control" value="{{old('name', $docente->name)}}">
                    </div> 
                </div>
                <div class="form-row">
                    <div class="col-xl-6 form-group">
                        <label for="email">*Email: </label>
                        <input type="email" name="email" class="form-control" value="{{old('email', $docente->email)}}">
                    </div>
                </div>
                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <input type="submit" class="btn btn-block btn-success" 
                        value="{{$docente->exists ? 'Editar docente' : 'Crear docente'}}">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/docentes/crear_editar.js')}}"></script>
@endsection