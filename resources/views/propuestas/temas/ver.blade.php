@extends('layouts.aplicacion')

@section('titulo', 'Propuesta de tema')
    
@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="d-inline m-0 font-weight-bold text-primary">Detalle tema</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Fecha: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$tema->created_at}}</div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Propuesto por: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$tema->docente->name}}</div>
                </div>
                @if ($tema->alumno_id)
                    <div class="row mb-2 mb-sm-0">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Solicitado por: </h5></div>
                        <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$tema->alumno->name}}</div>
                    </div>
                @endif
                <div class="row mb-2 mb-md-0">
                    <div class="col-12 col-md-4 col-lg-3"><h5>Titulo: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$tema->titulo}}</div>
                </div>
                <div class="row mb-2 mb-md-0">
                    <div class="col-12">
                        <h5>Descripción: </h5>
                        <p>{!! nl2br($tema->descripcion) !!}</p>
                    </div>
                </div>
                @if ($tema->tecnologias)
                    <div class="row mb-2 mb-md-0">
                        <div class="col-12">
                            <h5>Tecnologías: </h5>
                            <p>{!! nl2br($tema->tecnologias) !!}</p>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->can('propuestas.temas.solicitar') && auth()->user()->can('solicitar', App\Models\PropuestaTema::class))
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{route('temas.solicitar', $tema)}}" class="btn btn-primary btn-user">
                                Solicitar tema
                            </a>
                        </div>
                    </div>  
                @endif   
            </div>
        </div>    
    </div>
</div>
@endsection

@section('otros-scripts')

@endsection