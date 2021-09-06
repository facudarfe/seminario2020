@extends('layouts.aplicacion')

@section('titulo', 'Manual de usuario')
    
@section('titulo-contenido', 'Manual de usuario')

@section('contenido')
<div class="">
    <div class="row justify-content-center align-middle">
        <div class="col-12 col-md-5 text-center">
            @hasanyrole('Administrador|Docente responsable|Docente colaborador|Estudiante')
            <a href="{{asset('storage/Manuales/Manual_de_Usuario___Estudiantes.pdf')}}" target="_blank" class="btn btn-danger btn-lg btn-block mb-3">
                <i class="fas fa-file-pdf"></i> &nbsp;
                Manual de usuario - Estudiante
            </a>
            @endhasrole
            @hasanyrole('Administrador|Docente responsable|Docente colaborador|')
            <a href="{{asset('storage/Manuales/Manual_de_Usuario___DocentesColaboradores.pdf')}}" target="_blank" class="btn btn-danger btn-lg btn-block mb-3">
                <i class="fas fa-file-pdf"></i> &nbsp;
                Manual de usuario - Docente colaborador
            </a>
            @endhasrole
            @hasanyrole('Administrador|Docente responsable')
            <a href="{{asset('storage/Manuales/Manual_de_Usuario___DocentesResponsables.pdf')}}" target="_blank" class="btn btn-danger btn-lg btn-block mb-3">
                <i class="fas fa-file-pdf"></i> &nbsp;
                Manual de usuario - Docente responsable
            </a>
            @endhasrole
        </div>
    </div>
</div>
@endsection