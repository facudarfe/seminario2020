@extends('layouts.tabla')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('titulo', 'Presentaciones')  
@section('titulo-contenido', 'Presentaciones')

@section('titulo-tabla', 'Presentaciones')
    
@section('contenido-tabla')
    <thead>

    </thead>
    <tbody>

    </tbody>
@endsection

@section('otros-scripts')
    <!-- Datatables-->
    <script src="{{asset('sbadmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/dataTables.js')}}"></script>
@endsection