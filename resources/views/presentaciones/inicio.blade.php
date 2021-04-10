@extends('layouts.tabla')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.0.1/css/searchBuilder.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.0.2/css/dataTables.dateTime.min.css">
    
    <!--Estilo para datetimepicker-->
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">

    <!--Multiselect-->
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css">

    <style>
        /*Cambiar la posicion del input de busqueda del DataTable a la izquierda reescribiendo las clases*/
        div.dataTables_wrapper div.dataTables_filter{
            text-align: left;
        }
    </style>
@endsection

@section('titulo', 'Presentaciones')  
@section('titulo-contenido', 'Presentaciones')

@section('titulo-tabla', 'Proyectos')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    @foreach (auth()->user()->presentacionesPendientes as $pendiente)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info fade show">
                    <i class="fas fa-exclamation mr-1"></i>
                    Te han agregado como participante del trabajo <b>"{{$pendiente->titulo}}"</b>. Para aceptar o rechazar la 
                    participación en este proyecto ingresa al mismo y selecciona la opción.
                    <a href="{{route('presentaciones.ver', $pendiente)}}">Ver proyecto</a>
                </div>
            </div>
        </div>
    @endforeach
    @if (auth()->user()->can('crear', App\Models\Anexo1::class))
        @if ($solicitado)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-primary fade show">
                        Se detectó que solicitaste desarrollar una propuesta de tema o pasantia. Debes dirigirte a la sección
                        <b>Propuestas Temas/Pasantias</b> y en tu propuesta solicitada seleccionar la opción "Crear presentación". 
                        Si ya no querés dicha propuesta podes liberarla en la misma sección con la opción "Ya no lo quiero" para que esté
                        disponible para otro estudiante.
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-start mb-3">
                <div class="col-12">
                    <a href="{{route('presentaciones.crear')}}" class="btn btn-success">
                        Nueva presentación
                    </a>
                </div>
            </div>
        @endif  
    @endif
@endsection

@section('contenido-tabla')
    <table class="table" id="dataTable" width="100%" cellspacing="0" data-role="{{auth()->user()->getRoleNames()->first()}}">
        <thead>
            <tr>
                <th></th>
                <th>Fecha</th>
                <th>Titulo</th>
                @unlessrole('Estudiante')
                <th>Alumno/s</th>
                @endrole
                <th>Director</th>
                <th>Modalidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presentaciones as $presentacion)
                <tr>
                    <th><a href="{{route('presentaciones.ver', $presentacion)}}"><i class="fas fa-eye"></i></a></th>
                    <td>{{$presentacion->created_at}}</td>
                    <td>{{$presentacion->titulo}}</td>
                    @unlessrole('Estudiante')
                    <td>
                        @for ($i = 0; $i < count($presentacion->alumnos); $i++)
                            @if ($i != count($presentacion->alumnos)-1)
                                {{($presentacion->alumnos[$i])->name . '- '}}
                            @else
                                {{($presentacion->alumnos[$i])->name}}
                            @endif
                        @endfor
                    </td>
                    @endrole
                    <td>{{$presentacion->director->name}}</td>
                    <td>{{$presentacion->modalidad->nombre}}</td>
                    <td>
                        <span class="badge badge-{{$presentacion->estado->color_clase}}">{{$presentacion->estado->nombre}}</span>
                    </td>
                    <td class="text-center">
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down"></i></a>
                            <div class="dropdown-menu shadow activeOptions">
                                @can('subirInforme', $presentacion)
                                    <button class="dropdown-item" data-presentacion="{{$presentacion->id}}" id="botonInforme">
                                        <i class="fas fa-file-pdf fa-lg fa-fw text-gray-400"></i>
                                        Subir informe de avance
                                    </button>
                                @endcan
                                @if ($presentacion->ruta_informe)
                                    <a href="{{route('presentaciones.descargarInforme', $presentacion)}}" class="dropdown-item">
                                        <i class="fas fa-file-download fa-lg fa-fw text-gray-400"></i>
                                        Descargar informe de avance
                                    </a>
                                @endif
                                @can('proponerFecha', $presentacion)
                                    <a href="#" class="dropdown-item" id="botonPropuestaFecha" data-id="{{$presentacion->id}}">
                                        <i class="fas fa-calendar-alt fa-lg fa-fw text-gray-400"></i>
                                        Solicitar mesa examinadora
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('contenido-despues-tabla')
    @if(auth()->user()->can('anexos2.ver') && $anexos2->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Solicitudes mesa examinadora</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tablaAnexos2">
                        <thead>
                            <th></th>
                            <th>Fecha y hora propuesta</th>
                            <th>Fecha y hora definitiva</th>
                            <th>Titulo proyecto</th>
                            <th>Alumno/s</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @foreach ($anexos2 as $anexo2)
                                <tr>
                                    <th><a href="{{route('anexo2.ver', $anexo2)}}"><i class="fas fa-eye"></i></a></th>
                                    <td>{{$anexo2->fecha_propuesta}}</td>
                                    <td>{{$anexo2->fecha_definitiva}}</td>
                                    <td>{{$anexo2->presentacion->titulo}}</td>
                                    <td>
                                        @for ($i = 0; $i < count($anexo2->presentacion->alumnos); $i++)
                                            @if ($i != count($anexo2->presentacion->alumnos)-1)
                                                {{($anexo2->presentacion->alumnos[$i])->name . ' - '}}
                                            @else
                                                {{($anexo2->presentacion->alumnos[$i])->name}}
                                            @endif
                                        @endfor
                                    </td>
                                    <td><span class="badge badge-{{$anexo2->estado->color_clase}}">{{$anexo2->estado->nombre}}</span></td>
                                    <td class="text-center">
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down"></i></a>
                                            <div class="dropdown-menu shadow activeOptions">
                                                <a class="dropdown-item" href="{{route('presentaciones.ver', $anexo2->anexo1_id)}}">
                                                    <i class="fas fa-file-alt fa-lg fa-fw text-gray-400"></i>
                                                    Ver presentación asociada
                                                </a>
                                                @can('generarPDF', $anexo2)
                                                    <a class="dropdown-item" href="{{route('anexos2.PDF', $anexo2)}}" target="_blank">
                                                        <i class="fas fa-file-download fa-lg fa-fw text-gray-400"></i>
                                                        Descargar Anexo 2
                                                    </a>
                                                @endcan
                                                @if(auth()->user()->can('anexos2.definirFechaYTribunal') && $anexo2->estado->nombre == 'Fecha propuesta')
                                                    <button type="button" data-id="{{$anexo2->id}}" class="dropdown-item" id="botonDefinirFecha">
                                                        <i class="fas fa-calendar-alt fa-lg fa-fw text-gray-400"></i>
                                                        Definir fecha y tribunal
                                                    </button>
                                                @endif
                                                @if ($anexo2->ruta_informe && auth()->user()->can('ver', $anexo2))
                                                    <a href="{{route('anexos2.descargarInforme', $anexo2)}}" class="dropdown-item">
                                                        <i class="fas fa-file-download fa-lg fa-fw text-gray-400"></i>
                                                        Descargar informe final
                                                    </a>
                                                @endif
                                                @if (auth()->user()->can('anexos2.evaluar') && $anexo2->estado->nombre == 'Fecha y tribunal definidos')
                                                    <button type="button" data-id="{{$anexo2->id}}" id="evaluarExamen" class="dropdown-item" id="botonDefinirFecha">
                                                        <i class="fas fa-check-double fa-lg fa-fw text-gray-400"></i>
                                                        Evaluar exámen
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!--Modal para subir el archivo del informe-->
    <div class="modal fade" id="modalInforme" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir informe Presentación</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data" id="formInforme">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row justify-content-center">
                            <div class="col-11 form-group">
                                <label for="informe">Sube el informe en formato PDF:</label>
                                <input type="file" class="form-control" id="informe" name="informe">
                                {{-- <div class="input-group">
                                    <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="informe" id="informe">
                                    <label class="custom-file-label" for="informe">Subir informe</label>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="subir">Subir</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <!--Modal para solicitar mesa examinadora-->
    <div class="modal fade" id="modalFinal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Solicitar mesa examinadora</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="POST" id="formFecha" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row justify-content-center">
                            <div class="col-11 form-group">
                                <label for="informe_final">Sube el informe final en formato PDF:</label>
                                <input type="file" class="form-control" id="informe_final" name="informe_final">
                            </div>
                            <div class="col-11 form-group">
                                <label for="fecha">Proponga una fecha y hora para la evaluación del proyecto:</label>
                                <input type="text" class="form-control" id="fecha_propuesta" name="fecha_propuesta">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <p class="col-11 text-center">Se generará automaticamente el <b>Anexo 2</b> al solicitar la mesa examinadora.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="aceptar">
                            <i class="loading-icon fa fa-spinner fa-spin d-none"></i>
                            Solicitar
                        </button>
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <!--Modal para elegir fecha definitiva y tribunal evaluador-->
    <div class="modal fade" id="modalFinalTribunal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Definir fecha y tribunal</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="POST" id="formFechaTribunal">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row justify-content-center">
                            <div class="col-11 form-group">
                                <label for="fecha">Elija fecha y hora para el final:</label>
                                <input type="text" class="form-control" id="fecha_definitiva" name="fecha_definitiva">
                            </div>
                        </div>
                        
                        <div class="form-row justify-content-center">
                            <div class="form-group col-11">
                                <label for="grupo">Tribunal titular: </label>
                                <select name="tribunalTitular[]" class="custom-select tribunal" id="tribunalTitular" multiple>
                                    @foreach ($docentes as $docente)
                                        <option value="{{$docente->dni}}">{{$docente->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row justify-content-center">
                            <div class="form-group col-11">
                                <label for="grupo">Tribunal suplente: </label>
                                <select name="tribunalSuplente[]" class="custom-select tribunal" id="tribunalSuplente" multiple>
                                    @foreach ($docentes as $docente)
                                        <option value="{{$docente->dni}}">{{$docente->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="aceptar">Aceptar</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <!--Modal para realizar la correcion de un trabajo-->
    <div class="modal fade" id="modalEvaluar" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Evaluar exámen</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="POST" id="formEvaluacion">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-row justify-content-center">
                            <div class="col-10">
                                <div class="form-row justify-content-between">
                                    @foreach ($estadosEvaluacion as $estado)
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id={{"estado" . $estado->id}} name="estado" 
                                            value="{{$estado->id}}" class="custom-control-input">
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
                                <label for="observaciones">Devolución: </label>
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
@endsection

@section('otros-scripts')
    <!-- Datatables-->
    <script src="{{asset('sbadmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.0.1/js/dataTables.searchBuilder.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.0.1/js/searchBuilder.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.0.2/js/dataTables.dateTime.min.js"></script>

    <!--Multiselect-->
    <script src="{{asset('js/bootstrap-multiselect.js')}}"></script>

    @include('includes.scripts_validaciones')
    
    <!--Script adicional para jQuery validation para validar las extensiones de los archivos-->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.js"></script>

    <!--Script moment para manejo de tiempo en el datetimepicker-->
    <script src="{{asset('js/moment-with-locales.js')}}"></script>

    <!--Script para datetimepicker-->
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>

    <!--Script propio para este pagina-->
    <script src="{{asset('js/presentaciones/inicio.js')}}"></script>
@endsection