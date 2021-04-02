@extends('layouts.tabla')

@section('titulo', 'Cuerpo docente')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo-tabla', 'Cuerpo docente')

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
                    <td>A</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('otros-scripts')
    <!-- Datatables-->
    <script src="{{asset('sbadmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('js/docentes/inicio.js')}}"></script>
@endsection