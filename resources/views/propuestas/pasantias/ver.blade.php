@extends('layouts.aplicacion')

@section('titulo', 'Propuesta de pasantía')
    
@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="d-inline m-0 font-weight-bold text-primary">Detalle pasantía</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Fecha: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$pasantia->created_at}}</div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Propuesto por: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$pasantia->docente->name}}</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 col-md-4 col-lg-3"><h5>Titulo: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$pasantia->titulo}}</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 col-md-4 col-lg-3"><h5>Lugar: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$pasantia->lugar}}</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 col-md-4 col-lg-3"><h5>Duración: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$pasantia->duracion}} meses</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 col-md-4 col-lg-3"><h5>Cierre propuesta: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$pasantia->fecha_fin}}</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12">
                        <h5>Descripción: </h5>
                        <p>{!! nl2br($pasantia->descripcion) !!}</p>
                    </div>
                </div>
{{--                 @if (auth()->user()->can('propuestas.pasantias.solicitar') && auth()->user()->can('solicitar', $pasantia))
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{route('temas.solicitar', $tema)}}" class="btn btn-primary btn-user">
                                Solicitar pasantia
                            </a>
                        </div>
                    </div>  
                @endif   --}} 
            </div>
        </div>    
    </div>
</div>
@endsection

@section('otros-scripts')

@endsection