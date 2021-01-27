@extends('layouts.aplicacion')

@section('titulo', 'Presentacion')
    
@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="d-inline m-0 font-weight-bold text-primary">Detalle presentación</h6>
                <div class="d-inline float-right dropdown no-arrow">
                    <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        @if (auth()->user()->can('asignar.evaluador') && !$presentacion->docente_id)
                            <button class="dropdown-item" data-toggle="modal" data-target="#modalDocente">
                                Asignar docente evaluador
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3"><h5>Fecha presentacion: </h5></div>
                    <div class="col-9">{{$presentacion->fecha}}</div>
                </div>
                <div class="row">
                    <div class="col-3"><h5>Titulo: </h5></div>
                    <div class="col-9">{{$presentacion->titulo}}</div>
                </div>
                <div class="row">
                    <div class="col-3"><h5>Director: </h5></div>
                    <div class="col-9">{{$presentacion->director->name}}</div>
                </div>
                <div class="row">
                    <div class="col-3"><h5>Codirector: </h5></div>
                    <div class="col-9">{{$presentacion->codirector->name}}</div>
                </div>
                <div class="row">
                    <div class="col-3"><h5>Modalidad: </h5></div>
                    <div class="col-9">{{$presentacion->modalidad->nombre}}</div>
                </div>
                @if ($presentacion->docente_id)
                    <div class="row">
                        <div class="col-3"><h5>Docente evaluador: </h5></div>
                        <div class="col-9">{{$presentacion->evaluador->name}}</div>
                    </div>
                @endif
                @foreach ($presentacion->versiones as $version)
                    <div class="row">
                        <div class="card shadow w-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                <h6 class="m-0 font-weight-bold text-primary d-inline">Version {{$loop->index + 1}}</h6>
                                <span class="d-inline float-right badge badge-{{$version->estado->color_clase}}">{{$version->estado->nombre}}</span>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse {{$loop->last ? 'show' : ''}}" id="collapseCardExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Resumen: </h5>
                                            <p>{{$version->resumen}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h5>Tecnologias: </h5>
                                            <p>{{$version->tecnologias}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h5>Descripcion: </h5>
                                            <p>{{$version->descripcion}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>    
    </div>
</div>

<!--Modal para elegir el docente evaluador-->
<div class="modal fade" id="modalDocente" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Elegir docente evaluador</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('presentaciones.asignarEvaluador', $presentacion)}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row justify-content-center">
                        <div class="col-md-8 form-group">
                            <label for="docente">Docente evaluador: </label>
                            <select name="docente" id="docente" class="custom-select">
                                @foreach ($docentes as $docente)
                                    <option value="{{$docente->id}}" selected>{{$docente->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="confirmar">Asignar</button>
                </div>
            </form> 
        </div>
    </div>
</div>
@endsection