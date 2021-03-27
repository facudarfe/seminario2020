<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\PropuestaPasantia;
use Illuminate\Http\Request;

class PasantiasController extends Controller
{
    public function index(){
        if(auth()->user()->getRoleNames()->first() == 'Estudiante'){
            $pasantias = PropuestaPasantia::whereHas('estado', function($q){
                $q->where('nombre', 'Disponible');
            })->get();
        }
        else{
            $pasantias = PropuestaPasantia::all();
        }   

        return view('propuestas.pasantias.inicio', compact('pasantias'));
    }

    public function create(){
        return view('propuestas.pasantias.crear_editar', ['pasantia' => new PropuestaPasantia()]);
    }

    public function store(Request $request){
        $request->validate([
            'titulo' => ['required', 'max:255'],
            'lugar' => ['required', 'max:255'],
            'descripcion' => ['required'],
            'tutores' => ['required', 'max:255'],
            'duracion' => ['required', 'numeric', 'between:1,12'],
            'fecha_fin' => ['required', 'date', 'after:' . date('Y-m-d')],
        ]);

        $pasantia = new PropuestaPasantia();

        $pasantia->titulo = $request->get('titulo');
        $pasantia->lugar = $request->get('lugar');
        $pasantia->descripcion = $request->get('descripcion');
        $pasantia->tutores = $request->get('tutores');
        $pasantia->duracion = $request->get('duracion');
        $pasantia->fecha_fin = $request->get('fecha_fin');

        $pasantia->docente()->associate(auth()->user());

        $estado = Estado::where('nombre', 'Disponible')->first();
        $pasantia->estado()->associate($estado);

        $pasantia->save();

        return redirect()->route('pasantias.inicio')->with('exito', 'La propuesta de pasantia se ha creado con éxito');
    }
}
