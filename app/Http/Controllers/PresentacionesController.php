<?php

namespace App\Http\Controllers;

use App\Models\Anexo1;
use App\Models\Estado;
use App\Models\Modalidad;
use App\Models\User;
use App\Models\Version_Anexo1;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacionesController extends Controller
{
    public function index(){
        if(auth()->user()->hasRole('Estudiante')){
            $presentaciones = Anexo1::where('alumno_id', auth()->user()->id)->get();
        }
        elseif(auth()->user()->hasRole('Docente colaborador')){
            //$presentaciones = Anexo1::all()->versiones()->where('docente_id', auth()->user()->id)->get();
            
            //Para los docentes colaboradores solo se mostraran las presentaciones que le fueron asignadas para corregir
            $presentaciones = Anexo1::where('docente_id', auth()->user()->id)->get();
        }
        else{
            $presentaciones = Anexo1::all();
        }

        return view('presentaciones.inicio', compact('presentaciones'));
    }

    public function create(){
        $docentes = User::role(['Docente responsable', 'Docente colaborador'])->get();
        $modalidades = Modalidad::all();

        return view ('presentaciones.crear', compact('docentes', 'modalidades'));
    }

    public function store(Request $request){
        $encabezado = new Anexo1();
        $version = new Version_Anexo1();
        
        //Codigo para el encabezado
        $encabezado->titulo = $request->titulo;
        $encabezado->fecha = Carbon::now()->format('Y-m-d');
        $encabezado->alumno()->associate(auth()->user()); //Se le asocia a la presentacion el usuario que esta haciendo la carga
        
        //Asociar el director y codirector
        $director = User::find($request->get('director'));
        $encabezado->director()->associate($director);
        $codirector = User::find($request->get('codirector'));
        $encabezado->codirector()->associate($codirector);

        //Modalidad
        $modalidad = Modalidad::find($request->get('modalidad'));
        $encabezado->modalidad()->associate($modalidad);

        //Estado
        $estado = Estado::where('nombre', 'Pendiente')->get()->first();
        $encabezado->estado()->associate($estado);

        $encabezado->save();

        //Codigo para la version
        $version->anexo()->associate($encabezado);
        $version->resumen = $request->resumen;
        $version->tecnologias = $request->tecnologias;
        $version->descripcion = $request->descripcion;
        $version->estado()->associate($estado); //Se le asocia el mismo estado que el encabezado

        $version->save();

        return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha creado la presentacion con exito.');
    }

    public function show(Anexo1 $presentacion){
        if(auth()->user()->hasRole('Estudiante')){
            if(auth()->user()->id != $presentacion->alumno_id){
                abort(403);
            }
        }elseif(auth()->user()->hasRole('Docente colaborador')){
            if(auth()->user()->id != $presentacion->docente_id){
                abort(403);
            }
        }
        $docentes = User::role(['Docente responsable', 'Docente colaborador'])->get();
        return view('presentaciones.ver', compact('presentacion', 'docentes'));
    }

    public function asignarEvaluador(Request $request, Anexo1 $presentacion){
        $user = User::find($request->get('docente'));
        $presentacion->evaluador()->associate($user);

        $estado = Estado::where('nombre', 'Asignado')->first();
        $presentacion->estado()->associate($estado);

        $presentacion->save();

        return redirect(route('presentaciones.inicio'));
    }
}
