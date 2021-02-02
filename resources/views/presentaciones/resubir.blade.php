@extends('layouts.aplicacion')

@section('titulo', 'Crear presentacion')
    
@section('titulo-contenido', 'Nueva versión')

@section('contenido')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')

    <form action="{{route('presentaciones.resubirVersion', $presentacion)}}" method="POST" id="formPresentacion">
        @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="titulo">*Titulo proyecto: </label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{$presentacion->titulo}}" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="director">*Director: </label>
                        <input name="director" id="director" class="form-control" value="{{$presentacion->director->name}}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="codirector">*Co-director: </label>
                        <input name="codirector" id="codirector" class="form-control" value="{{$presentacion->codirector->name}}" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="modalidad">*Modalidad: </label>
                        <input name="modalidad" id="modalidad" class="form-control" value="{{$presentacion->modalidad->nombre}}" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="resumen">*Resumen: </label>
                        <textarea name="resumen" id="resumen" rows="7" class="form-control">{{old('resumen')}}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="tecnologias">*Tecnologias: </label>
                        <textarea name="tecnologias" id="tecnologias" rows="1" class="form-control">{{old('tecnologias')}}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="descripcion">*Descripcion detallada del proyecto: </label>
                        <textarea name="descripcion" id="descripcion" rows="10" class="form-control">{{old('descripcion')}}</textarea>
                    </div>
                </div>
                <small>*Campos obligatorios</small>
                <div class="form-row py-3 justify-content-end">
                    <div class="col-xl-3 form-group">
                        <a href="{{url()->previous()}}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                    <div class="col-xl-3 form-group">
                        <button type="submit" id="botonCarga" class="btn btn-block btn-success">Crear versión</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--Modal con el resumen de la presentacion-->
    <div class="modal fade" id="modalResumen" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resumen presentacion</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <h5 class="d-inline font-weight-bold mr-2">Titulo:</h5>
                        <p class="d-inline" id="titulo"></p>
                    </div>
                    <div class="d-inline-block mr-3">
                        <h5 class="d-inline font-weight-bold mr-2">Director:</h5>
                        <p class="d-inline" id="director"></p>
                    </div>
                    <div class="d-inline-block">
                        <h5 class="d-inline font-weight-bold mr-2">Codirector:</h5>
                        <p class="d-inline" id="codirector"></p>
                    </div>
                    <div>
                        <h5 class="d-inline font-weight-bold mr-2">Modalidad:</h5>
                        <p class="d-inline" id="modalidad"></p>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mr-2">Resumen:</h5>
                        <p id="resumen"></p>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mr-2">Tecnologias:</h5>
                        <p id="tecnologias"></p>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mr-2">Descripcion:</h5>
                        <p id="descripcion"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" id="confirmar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('otros-scripts')
    @include('includes.scripts_validaciones')
    <script src="{{asset('js/presentaciones/crear.js')}}"></script>
@endsection