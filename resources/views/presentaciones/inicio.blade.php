@extends('layouts.tabla')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.0.1/css/searchBuilder.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.0.2/css/dataTables.dateTime.min.css">
    <style>
        /*Cambiar la posicion del input de busqueda del DataTable a la izquierda reescribiendo las clases*/
        div.dataTables_wrapper div.dataTables_filter{
            text-align: left;
        }
    </style>
@endsection

@section('titulo', 'Presentaciones')  
@section('titulo-contenido', 'Presentaciones')

@section('titulo-tabla', 'Presentaciones')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
    @include('includes.mensajes_error')
    @can('presentaciones.crear')
        <div class="row justify-content-start mb-3">
            <div class="col-12">
                <a href="{{route('presentaciones.crear')}}" class="btn btn-primary">
                    Nueva presentación
                </a>
            </div>
        </div>
    @endcan
@endsection

@section('contenido-tabla')
<table class="table" id="dataTable" width="100%" cellspacing="0" data-role="{{auth()->user()->getRoleNames()->first()}}">
    <thead>
        <tr>
            <th></th>
            <th>Fecha</th>
            <th>Titulo</th>
            @unlessrole('Estudiante')
            <th>Alumno</th>
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
                <td>{{$presentacion->alumno->name}}</td>
                @endrole
                <td>{{$presentacion->director->name}}</td>
                <td>{{$presentacion->modalidad->nombre}}</td>
                <td>
                    <span class="badge badge-{{$presentacion->estado->color_clase}}">{{$presentacion->estado->nombre}}</span>
                </td>
                <td class="text-center">
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-menu shadow">
                            @can('subirInforme', $presentacion)
                                <button class="dropdown-item" data-presentacion="{{$presentacion->id}}" id="botonInforme">
                                    <i class="fas fa-file-pdf fa-lg fa-fw text-gray-400"></i>
                                    Subir informe
                                </button>
                            @endcan
                            @if ($presentacion->ruta_informe)
                                <a href="{{route('presentaciones.descargarInforme', $presentacion)}}" class="dropdown-item">
                                    <i class="fas fa-file-download fa-lg fa-fw text-gray-400"></i>
                                    Descargar informe
                                </a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!--Modal para subir el archivo del informe-->
<div class="modal fade" id="modalInforme" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir informe Presentación 1</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('presentaciones.subirInforme')}}" method="POST" enctype="multipart/form-data" id="formInforme">
                @csrf
                <input type="hidden" id="idPresentacion" name="idPresentacion">
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

    @include('includes.scripts_validaciones')
    
    <!--Script adicional para jQuery validation para validar las extensiones de los archivos-->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.js"></script>

    <!--Script propio para este pagina-->
    <script src="{{asset('js/presentaciones/inicio.js')}}"></script>
@endsection