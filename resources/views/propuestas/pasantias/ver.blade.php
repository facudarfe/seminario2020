@extends('layouts.aplicacion')

@section('titulo', 'Propuesta de pasantía')
    
@section('contenido')
@include('includes.mensajes_error')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="d-inline m-0 font-weight-bold text-primary">Detalle pasantía</h6>
                <h5 class="d-inline float-right"><span class="badge badge-{{$pasantia->estado->color_clase}}">{{$pasantia->estado->nombre}}</span></h5>
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
                    <div class="col-12 col-md-4 col-lg-3"><h5>Tutor/es: </h5></div>
                    <div class="col-12 col-md-8 col-lg-9">{{$pasantia->tutores}}</div>
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
                @can('propuestas.pasantias.crear')
                    <div class="row mb-2 mb-md-0">
                        <div class="col-12">
                            <div class="mb-2">
                                <h5 class="d-inline">Solicitudes: </h5>
                                <a href={{route('pasantias.generarPDF', $pasantia)}} target="_blank" class="d-inline float-right" style="color:rgb(236, 75, 75);">
                                    <i class="fas fa-file-pdf mr-1" style="color: rgb(236, 75, 75);"></i>PDF postulantes
                                </a>
                            </div>
                            <table class="table table-sm shadow">
                                <thead>
                                    <th>Nombre</th>
                                    <th>E-mail</th>
                                    <th>Curriculum Vitae</th>
                                </thead>
                                <tbody>
                                    @foreach ($pasantia->alumnos as $alumno)
                                        <tr>
                                            <td>{{$alumno->name}}</td>
                                            <td>{{$alumno->email}}</td>
                                            <td>
                                                <a href="{{asset(Illuminate\Support\Facades\Storage::url($alumno->pivot->ruta_cv))}}" target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i>Descargar CV
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endcan
                @if (auth()->user()->can('propuestas.pasantias.solicitar') && auth()->user()->can('solicitar', $pasantia))
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="#" class="btn btn-primary btn-user" data-toggle="modal" data-target="#modalCV">
                                Solicitar pasantia
                            </a>
                        </div>
                    </div>  
                @endif
            </div>
        </div>    
    </div>
</div>

<!--Modal para subir el CV en la solicitud de pasantia-->
<div class="modal fade" id="modalCV" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Curriculum Vitae</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('pasantias.solicitar', $pasantia)}}" method="POST" enctype="multipart/form-data" id="formCV">
                @csrf
                <div class="modal-body">
                    <div class="form-row justify-content-center">
                        <div class="col-11 form-group">
                            <label for="CV">Sube el CV en formato PDF:</label>
                            <input type="file" class="form-control" id="CV" name="CV">
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
    @include('includes.scripts_validaciones')

    <!--Script adicional para jQuery validation para validar las extensiones de los archivos-->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <!--Script propio para este pagina-->
    <script src="{{asset('js/propuestas/pasantias/ver.js')}}"></script>
@endsection