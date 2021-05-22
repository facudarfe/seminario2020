<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\User;
use App\Models\Modalidad;
use App\Models\PropuestaTema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try{
            DB::transaction(function () use($tema){
                $tema->alumno_id = auth()->user()->id;

                $estado = Estado::where('nombre', 'Solicitado')->first();
                $tema->estado()->associate($estado);
        
                $tema->save();
            });

            return redirect()->route('temas.inicio')
                        ->with('exito', 'Ha solicitado el tema exitosamente. No te olvides de realizar la presentación correspondiente.');  
        }
        catch(Exception $e){
            return redirect()->route('temas.inicio')->withErrors('Ha ocurrido un error: ' . $e->getMessage());  
        }
    }

    public function free(PropuestaTema $tema){
        $tema->alumno()->dissociate();

        $estado = Estado::where('nombre', 'Disponible')->first();
        $tema->estado()->associate($estado);

        $tema->save();

        return redirect()->route('temas.inicio')->with('exito', 'Se ha liberado el tema con éxito.');
    }

    public function createPresentation(PropuestaTema $tema){
        $docentes = User::role(['Docente responsable', 'Docente colaborador'])->get();
        $modalidades = Modalidad::all();

        //Seleccionar los estudiantes que se pueden elegir para hacer trabajo juntos
        $c1 = User::role('Estudiante')->where('id', '!=', auth()->id())->doesntHave('presentaciones')->get();
        $c2 = User::role('Estudiante')->where('id', '!=', auth()->id())->whereHas('presentaciones', function($q){
            $q->whereHas('estado', function($q2){
                $q2->where('nombre', 'Rechazado');
            });
        })->get();
        $grupo = $c1->concat($c2);

        return view('presentaciones.crear', compact('docentes', 'modalidades', 'tema', 'grupo'));
    }
}
