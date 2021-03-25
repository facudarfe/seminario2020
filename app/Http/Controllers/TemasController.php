<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\PropuestaTema;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class TemasController extends Controller
{
    public function index(){
        //Si el usuario tiene una propuesta de tema solicitada se obtiene para mostrarla
        $solicitado = auth()->user()->propuestaTema()->whereHas('estado', function($q){
                                        $q->where('nombre', '=', 'Solicitado');
                                    })->first();

        if(auth()->user()->getRoleNames()->first() == 'Estudiante'){
            $temas = PropuestaTema::whereHas('estado', function($q){
                $q->where('nombre', 'Disponible');
            })->get();
        }
        else{
            $temas = PropuestaTema::all();
        }   

        return view('propuestas.temas.inicio', compact('temas', 'solicitado'));
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
        
        try{
            $tema->update($request->all());

            return redirect()->route('temas.inicio')->with('exito', 'Se ha actualizado el tema con éxito.');
        }
        catch(Exception $e){
            return redirect()->route('temas.inicio')->withErrors('No se puede editar o eliminar un tema ya asignado.');
        }
    }

    public function destroy(PropuestaTema $tema){
        try{
            $tema->delete();

            return redirect()->route('temas.inicio')->with('exito', 'Se ha eliminado el tema con éxito.');
        }
        catch(Exception $e){
            return redirect()->route('temas.inicio')->withErrors('No se puede editar o eliminar un tema ya asignado.');
        }
    }

    public function show(PropuestaTema $tema){
        return view('propuestas.temas.ver', compact('tema'));
    }

    public function request(PropuestaTema $tema){
        $tema->alumno_id = auth()->user()->id;

        $estado = Estado::where('nombre', 'Solicitado')->first();
        $tema->estado()->associate($estado);

        $tema->save();

        return redirect()->route('temas.inicio')
                ->with('exito', 'Ha solicitado el tema exitosamente. No te olvides de realizar la presentación correspondiente.');
    }

    public function free(PropuestaTema $tema){
        $tema->alumno()->dissociate();

        $estado = Estado::where('nombre', 'Disponible')->first();
        $tema->estado()->associate($estado);

        $tema->save();

        return redirect()->route('temas.inicio')->with('exito', 'Se ha liberado el tema con éxito.');
    }
}
