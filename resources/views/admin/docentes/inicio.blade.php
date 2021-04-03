@extends('layouts.tabla')

@section('titulo', 'Cuerpo docente')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo-contenido', 'Docentes')

@section('titulo-tabla', 'Cuerpo docente')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 col-xl-2">
            <a href="{{route('docentes.crear')}}" class="btn btn-block btn-success mb-3">Nuevo docente</a>
        </div>
    </div>
@endsection

@section('contenido-tabla')
    <table class="table" id="tablaDocentes">
        <thead>
            <th>Documento</th>
            <th>Nombre</th>
            <th>E-mail</th>
            <th>Acciones</th>
        </thead>
        <tbody>
            @foreach ($docentes as $docente)
                <tr>
                    <td>{{$docente->dni}}</td>
                    <td>{{$docente->name}}</td>
                    <td>{{$docente->email}}</td>
                    <td class="text-center">
                        @can ('editarOEliminar', $docente)
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down btn-accion"></i></a>
                                <div class="dropdown-menu shadow activeOptions">
                                        <a href="{{route('docentes.editar', $docente)}}" class="dropdown-item">
                                            <i class="fas fa-pencil-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                            Editar
                                        </a>
                                        <button id="botonElimina" data-dni="{{$docente->dni}}" class="dropdown-item">
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
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar docente</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Esta seguro que desea eliminar a este docente?
                        <input type="hidden" id="input_user_id" name="user_id">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
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

    <script src="{{asset('js/docentes/inicio.js')}}"></script>
@endsection