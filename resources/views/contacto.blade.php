@extends('layouts.aplicacion')

@section('titulo', 'Contacto')
    
@section('titulo-contenido', 'Formulario de contacto')

@section('otros-estilos')
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css">
@endsection

@section('contenido')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form action="{{route('contacto.enviar')}}" method="POST" id="formContacto">
        @csrf
        <div class="row">
            <div class="col-xl-8">
                @can('contactar.usuario')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="grupo">Receptor/es: </label>
                            <select name="receptores[]" id="receptores" class="custom-select" multiple>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{$usuario->id}}">{{$usuario->name}} ({{$usuario->getRoleNames()->first()}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endcan
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">Asunto: </label>
                        <input type="text" class="form-control" id="asunto" name="asunto" value="{{old('asunto')}}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="resumen">Descripción: </label>
                        <textarea name="descripcion" id="descripcion" rows="7" class="form-control">{{old('descripcion')}}</textarea>
                    </div>
                </div>

                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <button type="submit" id="botonCarga" class="btn btn-block btn-success">
                            <i class="loading-icon fa fa-spinner fa-spin mr-1 d-none"></i>Enviar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/bootstrap-multiselect.js')}}"></script>
    <script src="{{asset('js/contacto.js')}}"></script>
@endsection