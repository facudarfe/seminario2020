@extends('layouts.aplicacion')

@section('contenido')
    @yield('contenido-antes-tabla')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">@yield('titulo-tabla')</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    @yield('contenido-tabla')
                </table>
            </div>
        </div>
    </div>
    @yield('contenido-despues-tabla')
@endsection