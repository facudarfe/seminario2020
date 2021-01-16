@extends('layouts.aplicacion')

@section('titulo', 'Inicio')

@section('titulo-contenido', 'Pagina en blanco')

@section('contenido')
    Bienvenidos al sistema de Seminarios
    {{Auth::user()->getRoleNames()->first()}}
@endsection