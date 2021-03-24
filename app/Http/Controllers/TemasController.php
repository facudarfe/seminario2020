<?php

namespace App\Http\Controllers;

use App\Models\Estado;
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

    public function create(){
        return view('propuestas.temas.crear_editar', ['tema' => new PropuestaTema()]);
    }

    public function store(Request $request){
        $request->validate([
            'titulo' => ['required', 'max:255'],
            'descripcion' => ['required', 'max:500'],
            'tecnologias' => ['max:255'],
        ]);

        $tema = new PropuestaTema();

        $tema->titulo = $request->input('titulo');
        $tema->descripcion = $request->descripcion;
        $tema->tecnologias = $request->tecnologias;
        $tema->docente()->associate(auth()->user());

        $estado = Estado::where('nombre', 'Disponible')->first();
        $tema->estado()->associate($estado);
        $tema->save();

        return redirect(route('temas.inicio'))->with('exito', 'Se ha creado la propuesta de tema con éxito.');
    }

    public function edit(PropuestaTema $tema){
        return view('propuestas.temas.crear_editar', compact('tema'));
    }

    public function update(Request $request, PropuestaTema $tema){
        $request->validate([
            'titulo' => ['required', 'max:255'],
            'descripcion' => ['required', 'max:500'],
            'tecnologias' => ['max:255'],
        ]);
        
        $tema->update($request->all());

        return redirect()->route('temas.inicio')->with('exito', 'Se ha actualizado el tema con éxito.');
    }

    public function destroy(PropuestaTema $tema){
        $tema->delete();

        return redirect()->route('temas.inicio')->with('exito', 'Se ha eliminado el tema con éxito.');
    }

    public function show(PropuestaTema $tema){
        return view('propuestas.temas.ver', compact('tema'));
    }
}
