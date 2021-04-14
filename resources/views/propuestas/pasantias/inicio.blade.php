@extends('layouts.tabla')

@section('titulo', 'Propuestas de pasantías')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo-contenido', 'Propuestas de pasantías')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    @role('Estudiante')
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissable fade show">
                    <i class="fas fa-info-circle mr-1"></i>
                    Para solicitar una pasantía debes ingresar al detalle del tema que te interesa y hacer click en el botón 
                    <b>"Solicitar pasantía"</b>.
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                </div>
            </div>
        </div>
    @endrole
    @can ('propuestas.pasantias.crear')
        <div class="row justify-content-start mb-3">
            <div class="col-12">
                <a href="{{route('pasantias.crear_editar')}}" class="btn btn-success">
                    Nueva pasantía
                </a>
            </div>
        </div>
    @endcan

    <!--Si el usuario ya tiene una propuesta solicitada se le muestra-->
    @if (count(auth()->user()->propuestasPasantias))
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pasantias solicitadas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <th></th>
                            <th>Fecha</th>
                            <th>Titulo</th>
                            <th>Lugar</th>
                            <th>Duración</th>
                            <th>Fin propuesta</th>
                            <th>Propuesto por</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @foreach (auth()->user()->propuestasPasantias as $pasantia)
                                <tr>
                                    <td><a href="{{route('pasantias.ver', $pasantia)}}"><i class="fas fa-eye"></i></a></td>
                                    <td>{{$pasantia->created_at}}</td>
                                    <td>{{$pasantia->titulo}}</td>
                                    <td>{{$pasantia->lugar}}</td>
                                    <td>{{$pasantia->duracion}} meses</td>
                                    <td>{{$pasantia->fecha_fin}}</td>
                                    <td>{{$pasantia->docente->name}}</td>
                                    <td class="text-center">
                                        @can('liberar', $pasantia)
                                            <div class="dropdown no-arrow">
                                                <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down btn-accion"></i></a>
                                                <div class="dropdown-menu shadow activeOptions">
                                                    <a href="#" id="botonBaja" class="dropdown-item" data-id="{{$pasantia->id}}">
                                                        Darse de baja
                                                    </a>
                                                </div>
                                            </div>
                                        @endcan  
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal liberar pasantia-->
    <div class="modal fade" id="liberarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Darse de baja pasantía</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Esta seguro que desea darse de baja de esta pasantía?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a href="" class="btn btn-primary" id="darBaja">Darse de baja</a>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('titulo-tabla', 'Pasantías disponibles')
@section('contenido-tabla')
    <table class="table" id="tablaTemas">
        <thead>
            <th></th>
            <th>Fecha</th>
            <th>Titulo</th>
            <th>Lugar</th>
            <th>Duración</th>
            <th>Fin de propuesta</th>
            <th>Propuesto por</th>
            <th>Estado</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            @foreach ($pasantias as $pasantia)
                <tr>
                    <td><a href="{{route('pasantias.ver', $pasantia)}}"><i class="fas fa-eye"></i></a></td>
                    <td>{{$pasantia->created_at}}</td>
                    <td>{{$pasantia->titulo}}</td>
                    <td>{{$pasantia->lugar}}</td>
                    <td>{{$pasantia->duracion}} meses</td>
                    <td>{{$pasantia->fecha_fin}}</td>
                    <td>{{$pasantia->docente->name}}</td>
                    <td>
                        <span class="badge badge-{{$pasantia->estado->color_clase}}">{{$pasantia->estado->nombre}}</span>
                    </td>
                    <td class="text-center">
                        @can('manipular', $pasantia)
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down btn-accion"></i></a>
                                <div class="dropdown-menu shadow activeOptions">
                                    <a href="{{route('pasantias.editar', $pasantia)}}" class="dropdown-item">
                                            <i class="fas fa-pencil-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Editar
                                    </a>
                                    <button id="botonElimina" data-id="{{$pasantia->id}}" class="dropdown-item">
                                            <i class="fas fa-trash-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Eliminar
                                    </button>
                                    @if ($pasantia->estado->nombre == 'Cerrado')
                                        <a href="#" data-id={{$pasantia->id}} id="habilitar" class="dropdown-item">
                                            <i class="fas fa-lock-open fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Poner disponible
                                        </a>
                                    @endif
                                    @if ($pasantia->estado->nombre == 'Disponible')
                                        <a href="#" data-id={{$pasantia->id}} id="deshabilitar" class="dropdown-item">
                                            <i class="fas fa-lock fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Cerrar propuesta
                                        </a>
                                    @endif 
                                </div>
                            </div>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('contenido-despues-tabla')
    <!-- Modal eliminacion-->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar pasantía</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Esta seguro que desea eliminar esta pasantía?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" id="botonElimina">Eliminar</button>
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

    <!--Script propio para este pagina-->
    <script src="{{asset('js/propuestas/pasantias/inicio.js')}}"></script>
@endsection