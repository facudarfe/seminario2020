<?php

namespace App\Http\Controllers;

use App\Models\PropuestaPasantia;
use Illuminate\Http\Request;

class PasantiaController extends Controller
{
    public function index(){
        //Si el usuario tiene una propuesta de pasantia solicitada se obtiene para mostrarla
        $pasantiaSolic = auth()->user()->propuestaPasantia()->whereHas('estado', function($q){
            $q->where('nombre', '=', 'Solicitado');
        })->first();

        if(auth()->user()->getRoleNames()->first() == 'Estudiante'){
            $pasantias = PropuestaPasantia::whereHas('estado', function($q){
                $q->where('nombre', 'Disponible');
            })->get();
        }
        else{
            $pasantias = PropuestaPasantia::all();
        }   

        return view('propuestas.pasantias.inicio', compact('pasantias', 'pasantiaSolic'));
    }
}
