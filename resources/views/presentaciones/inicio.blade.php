@extends('layouts.tabla')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo', 'Presentaciones')  
@section('titulo-contenido', 'Presentaciones')

@section('titulo-tabla', 'Presentaciones')

@section('contenido-antes-tabla')
    @include('includes.mensaje_exito')
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
    <thead>
        <th></th>
        <th>Fecha</th>
        <th>Titulo</th>
        @unlessrole('Estudiante')
        <th>Alumno</th>
        @endrole
        <th>Director</th>
        <th>Modalidad</th>
        <th>Estado</th>
    </thead>
    <tbody>
        @foreach ($presentaciones as $presentacion)
            <tr>
                <th><a href="{{route('presentaciones.ver', $presentacion)}}"><i class="fas fa-eye"></i></a></th>
                <td>{{$presentacion->fecha}}</td>
                <td>{{$presentacion->titulo}}</td>
                @unlessrole('Estudiante')
                <td>{{$presentacion->alumno->name}}</td>
                @endrole
                <td>{{$presentacion->director->name}}</td>
                <td>{{$presentacion->modalidad->nombre}}</td>
                <td>
                    <span class="badge badge-{{$presentacion->estado->color_clase}}">{{$presentacion->estado->nombre}}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
@endsection

@section('otros-scripts')
    <!-- Datatables-->
    <script src="{{asset('sbadmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#dataTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                }
            });
        });
    </script>
@endsection