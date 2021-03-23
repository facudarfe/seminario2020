@extends('layouts.tabla')

@section('titulo', 'Propuestas de temas')

@section('otros-estilos')
    <link href="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('contenido-antes-tabla')
    
@endsection
@section('titulo-tabla', 'Temas disponibles')
@section('contenido-tabla')
    <table class="table" id="tablaTemas" data-role="{{auth()->user()->getRoleNames()->first()}}">
        <thead>
            <th></th>
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
                    <td>{{$tema->titulo}}</td>
                    <td>{{$tema->tecnologias}}</td>
                    <td>{{$tema->docente->name}}</td>
                    <td>
                        <span class="badge badge-{{$tema->estado->color_clase}}">{{$tema->estado->nombre}}</span>
                    </td>
                    <td>das</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('contenido-despues-tabla')
    
@endsection

@section('otros-scripts')
    <!-- Datatables-->
    <script src="{{asset('sbadmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!--Script propio para este pagina-->
    <script src="{{asset('js/propuestas/temas/inicio.js')}}"></script>
@endsection