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
                    <td><a href="#"><i class="fas fa-eye"></i></a></td>
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