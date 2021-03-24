@extends('layouts.aplicacion')

@section('titulo', 'Crear propuesta de tema')

@section('titulo-contenido', 'Crear nueva propuesta de tema')

@section('contenido')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form action="{{$tema->exists ? route('temas.actualizar', $tema) : route('temas.subir')}}" method="POST" id="formTemas">
        @csrf
        @if ($tema->exists)
            {{method_field('PUT')}}
        @endif
        <div class="row">
            <div class="col-xl-8">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">*Titulo tema: </label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{old('titulo', $tema->titulo)}}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="descripcion">*Descripcion: </label>
                        <textarea name="descripcion" id="descripcion" rows="10" class="form-control">{{old('descripcion', $tema->descripcion)}}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="tecnologias">Tecnologias: </label>
                        <textarea name="tecnologias" id="tecnologias" rows="1" class="form-control">{{old('tecnologias',$tema->tecnologias)}}</textarea>
                    </div>
                </div>

                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <button type="submit" id="botonCarga" class="btn btn-block btn-success">
                            {{$tema->exists ? 'Editar tema' : 'Crear tema'}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/propuestas/temas/crear_editar.js')}}"></script>
@endsection