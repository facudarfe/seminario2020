@extends('layouts.aplicacion')

@section('titulo', 'Roles y permisos')
    
@section('titulo-contenido', 'Roles y permisos')

@section('contenido')

@include('includes.mensaje_exito')
<form action="{{route('permisos.almacenar')}}" method="POST">
    @csrf
    <div class="row">
        <div class="col-12">
            @foreach ($roles as $rol)
            {{-- Recorro todos los roles y los muestro en forma de card --}}

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{$rol->id . '. ' . $rol->name}}</h6>
                </div>
                <div class="card-body">
                    @foreach ($permisos as $permiso)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="{{'permisos' . $rol->id . '[]'}}" value="{{$permiso}}" 
                            id="{{$permiso . $rol->id}}" {{$rol->hasPermissionTo($permiso) ? 'checked' : ''}}>
                            <label for="{{$permiso . $rol->id}}" class="custom-control-label">{{$permiso}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-12 text-right">
            <input type="submit" class="btn btn-success mr-auto" value="Actualizar permisos">
        </div>
    </div>
</form>

@endsection