<?php

namespace App\Http\Controllers;

use App\Mail\AsignarDocenteMail;
use App\Mail\CorreccionMail;
use App\Mail\NuevaPresentacionMail;
use App\Models\Anexo1;
use App\Models\Estado;
use App\Models\Modalidad;
use App\Models\User;
use App\Models\Version_Anexo1;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PresentacionesController extends Controller
{
    public function index(){
        //Se muestra la tabla con las presentaciones dependiendo del rol
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
        //Validacion de los campos
        $request->validate([
            'titulo' => ['required'],
            'resumen' => ['required'],
            'tecnologias' => ['required'],
            'descripcion' => ['required']
        ]);

        $encabezado = new Anexo1();
        $version = new Version_Anexo1();
        
        //Codigo para el encabezado
        $encabezado->titulo = $request->titulo;
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

        //Envio de mail al estudiante
        Mail::to($encabezado->alumno->email)->send(new NuevaPresentacionMail($encabezado->titulo));

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
        $estados = Estado::where('nombre', 'Resubir')->orWhere('nombre', 'Rechazado')->orWhere('nombre', 'Aprobado')->get();
        return view('presentaciones.ver', compact('presentacion', 'docentes', 'estados'));
    }

    public function asignarEvaluador(Request $request, Anexo1 $presentacion){
        $user = User::find($request->get('docente'));
        $presentacion->evaluador()->associate($user);

        $estado = Estado::where('nombre', 'Asignado')->first();
        $presentacion->estado()->associate($estado);

        $presentacion->save();

        //Enviar mail al evaluador
        Mail::to($user->email)->send(new AsignarDocenteMail($presentacion));

        return redirect(route('presentaciones.inicio'));
    }

    public function corregirVersion(Request $request){
        //Se obtiene el id de la version a traves del campo oculto 'version'
        $version = Version_Anexo1::find($request->input('version'));

        //Control de que se este por corregir una version Pendiente y que sea el docente correcto asignado
        if($version->estado->nombre == "Pendiente" && $version->anexo->docente_id == auth()->user()->id){
            $version->observaciones = $request->observaciones;
            $version->fecha_correccion = Carbon::now('America/Argentina/Salta')->format('Y-m-d');
            $estado = Estado::find($request->get('estado'));
            $version->estado()->associate($estado);
            
            $presentacion = $version->anexo;
            $presentacion->estado()->associate($estado);

            $version->save();
            $presentacion->save();

            //Enviar mail notificando la corrección
            Mail::to($version->anexo->alumno->email)->send(new CorreccionMail($version));

            return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha realizado la correción exitosamente.');
        }
        else{
            abort(403);
        }
    }

    public function resubir(Anexo1 $presentacion){
        return view('presentaciones.resubir', compact('presentacion'));
    }

    public function resubirVersion(Request $request, Anexo1 $presentacion){
        //Validacion de los campos del formulario
        $request->validate([
            'resumen' => ['required'],
            'tecnologias' => ['required'],
            'descripcion' => ['required']
        ]);

        if($request->user()->can('resubirVersion', $presentacion)){
            $version = new Version_Anexo1();

            $version->anexo()->associate($presentacion);
            $version->resumen = $request->resumen;
            $version->tecnologias = $request->tecnologias;
            $version->descripcion = $request->descripcion;
            
            $estado = Estado::where('nombre', 'Resubido')->first();
            $estado2 = Estado::where('nombre', 'Pendiente')->first();
            $presentacion->estado()->associate($estado);
            $version->estado()->associate($estado2);
    
            $presentacion->save();
            $version->save();

            return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha subido una nueva versión de la presentación.');
        }
        else{
            abort(403);
        }
    }

    public function regularizarPresentacion(Request $request, Anexo1 $presentacion){
        if($request->user()->can('presentaciones.regularizar') && $presentacion->estado->nombre == "Aprobado"){
            $presentacion->devolucion = $request->devolucion;
            $presentacion->fecha = Carbon::now('America/Argentina/Salta')->format('Y-m-d');
            $estado = Estado::where('nombre', 'Regular')->first();
            $presentacion->estado()->associate($estado);

            $presentacion->save();

            return redirect(route('presentaciones.inicio'))->with('exito', "Se ha regularizado el trabajo: $presentacion->titulo");
        }else{
            abort(403, 'No tienes permisos para regularizar esta presentación.');
        }
    }
}