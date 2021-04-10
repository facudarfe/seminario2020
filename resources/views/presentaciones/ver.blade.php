@extends('layouts.aplicacion')

@section('titulo', 'Presentacion')
    
@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="d-inline m-0 font-weight-bold text-primary">Detalle presentación</h6>
                <div class="d-inline float-right dropdown no-arrow">
                    <h5 class="d-none d-sm-inline mr-3"><span class="badge badge-{{$presentacion->estado->color_clase}}">{{$presentacion->estado->nombre}}</span></h5>
                    <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        @if (auth()->user()->can('presentaciones.asignar.evaluador') && !$presentacion->docente_id)
                            <button class="dropdown-item" data-toggle="modal" data-target="#modalDocente">
                                Asignar docente evaluador
                            </button>
                        @endif
                        @if (auth()->user()->can('presentaciones.regularizar') && $presentacion->estado->nombre == "Aceptado")
                        <button class="dropdown-item" data-toggle="modal" data-target="#modalRegularizar">
                            Regularizar trabajo
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                @can ('aceptarORechazar', $presentacion)
                    <div class="row mb-2 mb-sm-0">
                        <div class="col-12 text-center">
                            <button type="button" id="aceptarParticipacion" class="btn btn-sm btn-success">
                                <i class="fas fa-check mr-1"></i>Aceptar
                            </button>
                            <button type="button" id="rechazarParticipacion" class="btn btn-sm btn-danger">
                                <i class="fas fa-times mr-1"></i>Rechazar
                            </button>
                        </div>
                    </div>
                @endcan
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Fecha: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$presentacion->created_at}}</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 col-md-4 col-lg-3"><h5>Titulo: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$presentacion->titulo}}</div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Director: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$presentacion->director->name}}</div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Codirector: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$presentacion->codirector->name}}</div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Modalidad: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$presentacion->modalidad->nombre}}</div>
                </div>
                @if ($presentacion->docente_id)
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Docente evaluador: </h5></div>
                        <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$presentacion->evaluador->name}}</div>
                    </div>
                @endif
                <hr>
                @if ($presentacion->fecha)
                    <div class="row">
                        <div class="col-12">
                            <div class="jumbotron p-4 bg-success text-light mb-0">
                                <h5 class="d-inline font-weight-bold">Fecha finalización: </h5>
                                <p class="d-sm-inline">{{$presentacion->fecha}}</p>
                                <h5 class="mt-2 font-weight-bold">Devolución final: </h5>
                                <p>{{$presentacion->devolucion}}</p>
                            </div>
                        </div>
                    </div>
                    <hr>                
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
                                            <a href="{{route('pdf.anexo1', ['presentacion' => $presentacion, 'version' => $version])}}"  target="_blank"
                                            class="float-right" style="color:rgb(236, 75, 75);"><i class="fas fa-file-pdf mr-1" style="color: rgb(236, 75, 75);"></i>Obtener PDF</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Resumen: </h5>
                                            <p>{!! nl2br($version->resumen) !!}</p>
                                            <!--Se utiliza !! y la funcion nl2br() para que se muestren los saltos de linea y no se vea todo junto-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Tecnologias: </h5>
                                            <p>{!! nl2br($version->tecnologias) !!}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Descripcion: </h5>
                                            <p>{!! nl2br($version->descripcion) !!}</p>
                                            <!--Se utiliza !! y la funcion nl2br() para que se muestren los saltos de linea y no se vea todo junto-->
                                        </div>
                                    </div>
                                    @if ($version->estado->nombre != 'Pendiente')
                                        <div class="row">
                                            <div class="col-12">
                                                <h5>Corrección: </h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="jumbotron p-4">
                                                    <h5 class="d-inline">Fecha corrección: </h5>
                                                    <p class="d-sm-inline">{{$version->fecha_correccion}}</p>
                                                    <h5 class="mt-2">Observaciones: </h5>
                                                    <p>{{$version->observaciones}}</p>
                                                </div>
                                            </div>
                                        </div>                
                                    @endif
                                    @if (auth()->user()->can('presentaciones.corregir') && $version->estado->nombre == 'Pendiente' &&
                                    $presentacion->docente_id == auth()->user()->id)
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="button" id="botonCorreccion" data-version="{{$version->id}}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                    Realizar corrección
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @can('resubirVersion', $presentacion)
                    <div class="row">
                        <div class="text-center col-12">
                            <a href="{{route('presentaciones.resubir', $presentacion)}}" class="mt-3 btn btn-success">Subir nueva versión</a>
                        </div>
                    </div>
                @endcan
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

 <!--Modal para realizar la correcion de un trabajo-->
 <div class="modal fade" id="modalCorregir" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Realizar correción</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('presentaciones.corregir')}}" method="POST">
                @csrf
                <input type="hidden" name="version" id="version" value="1">
                <div class="modal-body">
                    <div class="form-row justify-content-center">
                        <div class="col-10">
                            <div class="form-row justify-content-between">
                                @foreach ($estados as $estado)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id={{"estado" . $estado->id}} name="estado" 
                                        value="{{$estado->id}}" class="custom-control-input" checked>
                                        <label for={{"estado" . $estado->id}} class="custom-control-label">
                                            <span class="badge badge-{{$estado->color_clase}}">{{$estado->nombre}}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-10">
                            <label for="observaciones">Observaciones: </label>
                            <textarea class="form-control form-control-user" name="observaciones" id="observaciones" cols="100%" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="confirmar">Aceptar</button>
                </div>
            </form> 
        </div>
    </div>
</div>

 <!--Modal para regularizar trabajo-->
 <div class="modal fade" id="modalRegularizar" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Regularizar trabajo</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('presentaciones.regularizar', $presentacion)}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row justify-content-center">
                        <div class="form-group col-10">
                            <label for="devolucion">Devoluciones finales: </label>
                            <textarea class="form-control form-control-user" name="devolucion" id="devolucion" cols="100%" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="confirmar">Aceptar</button>
                </div>
            </form> 
        </div>
    </div>
</div>

<!--Modal para aceptar o no participar en el trabajo-->
<div class="modal fade" id="modalParticipacion" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Participación trabajo</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('presentaciones.aceptarORechazar', [auth()->user(), $presentacion])}}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="tipo" id="tipo" value="">
                <div class="modal-body">
                    <p id="modal-body-text">¿Esta seguro de aceptar la participación en este proyecto?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="confirmar">Aceptar</button>
                </div>
            </form> 
        </div>
    </div>
</div>

@endsection

@section('otros-scripts')
    <script src="{{asset('js/presentaciones/ver.js')}}"></script>
@endsection