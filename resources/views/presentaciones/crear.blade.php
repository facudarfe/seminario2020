@extends('layouts.aplicacion')

@section('titulo', 'Crear presentacion')
    
@section('titulo-contenido', 'Nueva presentación')

@section('contenido')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form action="{{route('presentaciones.almacenar')}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">*Titulo proyecto: </label>
                        <input type="text" class="form-control" id="titulo" name="titulo">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="director">*Director: </label>
                        <select name="director" id="director" class="custom-select">
                            @foreach ($docentes as $docente)
                                <option value="{{$docente->id}}">{{$docente->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="codirector">*Co-director: </label>
                        <select name="codirector" id="codirector" class="custom-select">
                            @foreach ($docentes as $docente)
                                <option value="{{$docente->id}}">{{$docente->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="modalidad">*Modalidad: </label>
                        <select name="modalidad" id="modalidad" class="custom-select">
                            @foreach ($modalidades as $modalidad)
                                <option value="{{$modalidad->id}}" selected>{{$modalidad->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="resumen">*Resumen: </label>
                        <textarea name="resumen" id="resumen" rows="7" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="tecnologias">*Tecnologias: </label>
                        <textarea name="tecnologias" id="tecnologias" rows="1" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="descripcion">*Descripcion detallada del proyecto: </label>
                        <textarea name="descripcion" id="descripcion" rows="10" class="form-control"></textarea>
                    </div>
                </div>
                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <input type="submit" class="btn btn-block btn-success" value="Crear presentacion">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection