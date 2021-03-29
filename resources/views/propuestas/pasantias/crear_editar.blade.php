@extends('layouts.aplicacion')

@section('titulo')
    {{$pasantia->exists ? 'Editar tema' : 'Crear nueva propuesta de pasantía'}}
@endsection

@section('titulo-contenido')
    {{$pasantia->exists ? 'Editar tema' : 'Crear nueva propuesta de pasantía'}}
@endsection

@section('contenido')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form action="{{$pasantia->exists ? route('pasantias.actualizar', $pasantia) : route('pasantias.subir')}}"" method="POST" id="formPasantias">
        @csrf
        @if ($pasantia->exists)
            {{method_field('PUT')}}
        @endif
        <div class="row">
            <div class="col-xl-8">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">*Titulo pasantia: </label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{old('titulo', $pasantia->titulo)}}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">*Lugar: </label>
                        <input type="text" class="form-control" id="lugar" name="lugar" value="{{old('lugar', $pasantia->lugar)}}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="descripcion">*Descripcion: </label>
                        <textarea name="descripcion" id="descripcion" rows="10" class="form-control">{{old('descripcion', $pasantia->descripcion)}}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">*Tutor/es: </label>
                        <input type="text" class="form-control" id="tutores" name="tutores" value="{{old('tutores', $pasantia->tutores)}}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="tecnologias">*Duración en meses: </label>
                        <input type="number" class="form-control" name="duracion" id="duracion" value="{{old('duracion', $pasantia->duracion)}}">
                    </div>
                    <div class="form-group col-6">
                        <label for="tecnologias">*Fecha cierre propuesta: </label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="{{old('fecha_fin', $pasantia->getRawOriginal('fecha_fin'))}}">
                    </div>
                </div>

                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <button type="submit" id="botonCarga" class="btn btn-block btn-success">
                            {{$pasantia->exists ? 'Editar pasantía' : 'Crear pasantía'}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/propuestas/pasantias/crear_editar.js')}}"></script>
@endsection