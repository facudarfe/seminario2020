@extends('layouts.tabla')

@section('titulo', 'Usuarios')
    
@section('titulo-contenido', 'Usuarios')
    
@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo-tabla', 'Usuarios registrados')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 col-xl-2">
            <a href="{{route('usuarios.crear')}}" class="btn btn-block btn-success mb-3">Nuevo usuario</a>
        </div>
    </div>
@endsection

@section('contenido-tabla')
<table class="table" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Dirección</th>
            <th>Telefono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{$user->dni}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->getRoleNames()->first()}}</td>
            <td>{{$user->direccion}}</td>
            <td>{{$user->telefono}}</td>
            <td class="text-center">
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu shadow">
                        @can('manipularRol', $user->roles->first())
                            <a href="{{route('usuarios.editar', $user)}}" class="dropdown-item">
                                <i class="fas fa-pencil-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                Editar
                            </a>
                            <button id="botonElimina" data-user_id="{{$user->id}}" class="dropdown-item">
                                <i class="fas fa-trash-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                Eliminar
                            </button>
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
    <!-- Modal eliminacion-->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('usuarios.eliminar')}}" method="POST">
                    @csrf @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar usuario</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Esta seguro que desea eliminar a este usuario?
                        <input type="hidden" id="input_user_id" name="user_id">
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

    <!-- Page level custom scripts -->
    <script src="{{asset('js/dataTables.js')}}"></script>

    <script>
        $(document).on('click', '#botonElimina', function(){
            var user_id = $(this).attr('data-user_id');
            $('#deleteModal').modal('show');
            $('#deleteModal').ready(function(){
                $('#input_user_id').val(user_id);
            });
        });  
    </script>
@endsection