@extends('layouts.tabla')

@section('titulo', 'Propuestas de temas')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo-contenido', 'Propuestas de temas')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    @can ('propuestas.temas.crear')
        <div class="row justify-content-start mb-3">
            <div class="col-12">
                <a href="{{route('temas.crear_editar')}}" class="btn btn-success">
                    Nuevo tema
                </a>
            </div>
        </div>
    @endcan

    <!--Si el usuario ya tiene una propuesta solicitada se le muestra-->
    @if ($solicitado != null)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tema solicitado</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <th></th>
                            <th>Fecha</th>
                            <th>Titulo</th>
                            <th>Tecnologias</th>
                            <th>Propuesto por</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="{{route('temas.ver', $solicitado)}}"><i class="fas fa-eye"></i></a></td>
                                <td>{{$solicitado->created_at}}</td>
                                <td>{{$solicitado->titulo}}</td>
                                <td>{{$solicitado->tecnologias}}</td>
                                <td>{{$solicitado->docente->name}}</td>
                                <td class="text-center">
                                    @if ($solicitado->estado->nombre == 'Solicitado')
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down btn-accion"></i></a>
                                            <div class="dropdown-menu shadow activeOptions">
                                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#liberarModal">
                                                    Ya no lo quiero
                                                </a>
                                                <a href="#" class="dropdown-item">
                                                    Crear presentación
                                                </a>
                                            </div>
                                        </div>
                                    @endif   
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal liberar tema-->
        <div class="modal fade" id="liberarModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Liberar tema</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿Esta seguro que desea liberar el tema solicitado?
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <a href="{{route('temas.liberar', $solicitado)}}" class="btn btn-primary">Liberar</a>
                        </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('titulo-tabla', 'Temas disponibles')
@section('contenido-tabla')
    <table class="table" id="tablaTemas" data-role="{{auth()->user()->getRoleNames()->first()}}">
        <thead>
            <th></th>
            <th>Fecha</th>
            <th>Titulo</th>
            <th>Tecnologias</th>
            <th>Propuesto por</th>
            <th>Estado</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            @foreach ($temas as $tema)
                <tr>
                    <td><a href="{{route('temas.ver', $tema)}}"><i class="fas fa-eye"></i></a></td>
                    <td>{{$tema->created_at}}</td>
                    <td>{{$tema->titulo}}</td>
                    <td>{{$tema->tecnologias}}</td>
                    <td>{{$tema->docente->name}}</td>
                    <td>
                        <span class="badge badge-{{$tema->estado->color_clase}}">{{$tema->estado->nombre}}</span>
                    </td>
                    <td class="text-center">
                        @can('manipular', $tema)
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down btn-accion"></i></a>
                                <div class="dropdown-menu shadow activeOptions">
                                    <a href="{{route('temas.editar', $tema)}}" class="dropdown-item">
                                            <i class="fas fa-pencil-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Editar
                                    </a>
                                    <button id="botonElimina" data-id="{{$tema->id}}" class="dropdown-item">
                                            <i class="fas fa-trash-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Eliminar
                                    </button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar tema</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Esta seguro que desea eliminar a este tema?
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
    <script src="{{asset('js/propuestas/temas/inicio.js')}}"></script>
@endsection