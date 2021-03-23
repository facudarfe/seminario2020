<?php

namespace App\Http\Controllers;

use App\Models\PropuestaTema;
use Illuminate\Http\Request;

class TemasController extends Controller
{
    public function index(){
        if(auth()->user()->getRoleNames()->first == 'Estudiante'){
            $temas = PropuestaTema::whereHas('estado', function($q){
                $q->where('nombre', 'Disponible');
            })->get();
        }
        else{
            $temas = PropuestaTema::all();
        }   

        return view('propuestas.temas.inicio', compact('temas'));
    }
}
