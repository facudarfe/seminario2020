@extends('layouts.aplicacion')

@section('titulo', 'Mesa examinadora')
    
@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="d-inline m-0 font-weight-bold text-primary">Detalle mesa examinadora</h6>
                <h5 class="d-none d-sm-inline float-right mr-3"><span class="badge badge-{{$anexo2->estado->color_clase}}">{{$anexo2->estado->nombre}}</span></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-right">
                        @can('ver', $anexo2)
                            <a href="{{route('presentaciones.ver', $anexo2->presentacion)}}">
                                <i class="fas fa-file-alt"></i>
                                Ver presentación asociada
                            </a>
                        @endcan
                        @can('generarPDF', $anexo2)
                        -
                            <a href="{{route('anexos2.PDF', $anexo2)}}" target="_blank">
                                <i class="fas fa-file-download"></i>
                                Descargar Anexo 2
                            </a>
                        @endcan 
                    </div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Fecha y hora propuesta: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$anexo2->fecha_propuesta}}</div>
                </div>
                @if ($anexo2->fecha_definitiva)
                    <div class="row mb-2 mb-md-0">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Fecha y hora definitiva: </h5></div>
                        <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$anexo2->fecha_definitiva}}</div>
                    </div>
                @endif
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Titulo proyecto: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">{{$anexo2->presentacion->titulo}}</div>
                </div>
                <div class="row mb-2 mb-sm-0">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Alumno/s: </h5></div>
                    <div class="col-12 col-sm-6 col-md-8 col-lg-9">
                        @foreach ($anexo2->presentacion->alumnos as $alumno)
                            {{$alumno->name}} <br>
                        @endforeach
                    </div>
                </div>
                @if (count($anexo2->tribunal) > 0)
                    <div class="row mb-2 mb-sm-0">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Tribunal titular: </h5></div>
                        <div class="col-12 col-sm-6 col-md-8 col-lg-9">
                            @foreach ($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get() as $key => $docente)
                                @if ($key === count($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get())-1)
                                    {{$docente->name}}
                                @else
                                    {{$docente->name . ' - '}}
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="row mb-2 mb-sm-0">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3"><h5>Tribunal suplente: </h5></div>
                        <div class="col-12 col-sm-6 col-md-8 col-lg-9">
                            @foreach ($anexo2->tribunal()->where('tribunales_evaluadores.titular', false)->get() as $key => $docente)
                                @if ($key === count($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get())-1)
                                    {{$docente->name}}
                                @else
                                    {{$docente->name . ' - '}}
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>    
    </div>
</div>
@endsection

@section('otros-scripts')
    <script src="{{asset('js/presentaciones/ver.js')}}"></script>
@endsection