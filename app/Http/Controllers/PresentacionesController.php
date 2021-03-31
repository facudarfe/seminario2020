<?php

namespace App\Http\Controllers;

use App\Mail\AsignarDocenteMail;
use App\Mail\CorreccionMail;
use App\Mail\NuevaPresentacionMail;
use App\Models\Anexo1;
use App\Models\Estado;
use App\Models\Modalidad;
use App\Models\PropuestaTema;
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
            $presentaciones = auth()->user()->presentaciones;
            //$presentaciones = Anexo1::where('alumno_id', auth()->user()->id)->get();
        }
        elseif(auth()->user()->hasRole('Docente colaborador')){            
            //Para los docentes colaboradores solo se mostraran las presentaciones que le fueron asignadas para corregir
            $presentaciones = Anexo1::where('docente_id', auth()->user()->id)->get();
        }
        else{
            $presentaciones = Anexo1::all();
        }

        $solicitado = auth()->user()->propuestaTema()->whereHas('estado', function($q){
                                        $q->where('nombre', '=', 'Solicitado');
                                    })->first();

        return view('presentaciones.inicio', compact('presentaciones', 'solicitado'));
    }

    public function create(){
        $docentes = User::role(['Docente responsable', 'Docente colaborador'])->get();
        $modalidades = Modalidad::all();
        $tema = new PropuestaTema();

        //Seleccionar los estudiantes que se pueden elegir para hacer trabajo juntos
        $c1 = User::role('Estudiante')->where('id', '!=', auth()->id())->doesntHave('presentaciones')->get();
        $c2 = User::role('Estudiante')->where('id', '!=', auth()->id())->whereHas('presentaciones', function($q){
            $q->whereHas('estado', function($q2){
                $q2->where('nombre', 'Rechazado');
            });
        })->get();
        $grupo = $c1->concat($c2);

        return view ('presentaciones.crear', compact('docentes', 'modalidades', 'tema', 'grupo'));
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

        //Asociar al alumno que creo la presentacion y si los hubiera a los compañeros
        $encabezado->alumnos()->attach(auth()->id());
        if($modalidad->nombre == 'Seminario' && isset($request->checkGrupal) && isset($request->grupo)){
            $encabezado->alumnos()->attach($request->get('grupo'), ['aceptado' => false]);
        }

        //Codigo para la version
        $version->anexo()->associate($encabezado);
        $version->resumen = $request->resumen;
        $version->tecnologias = $request->tecnologias;
        $version->descripcion = $request->descripcion;
        $version->estado()->associate($estado); //Se le asocia el mismo estado que el encabezado

        $version->save();

        //Si la presentacion proviene de una propuesta de tema o pasantia se le cambia el estado
        $tema = $request->user()->propuestaTema()->whereHas('estado', function($q){
            $q->where('nombre', 'Solicitado');
        })->first();
        if($tema){
            $estado = Estado::where('nombre', 'Asignado')->first();
            $tema->estado()->associate($estado);
            $tema->save();
        }

        //Envio de mail al estudiante o estudiantes
        foreach($encabezado->alumnos as $alumno){
            Mail::to($alumno->email)->send(new NuevaPresentacionMail($encabezado->titulo));
        }

        return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha creado la presentacion con exito.');
    }

    public function show(Anexo1 $presentacion){
        $docentes = User::role(['Docente responsable', 'Docente colaborador'])->get();
        $estados = Estado::where('nombre', 'Resubir')->orWhere('nombre', 'Rechazado')->orWhere('nombre', 'Aceptado')->get();
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

    public function regularizarPresentacion(Request $request, Anexo1 $presentacion){
        //Control de que se este por regularizar una presentacion en estado Aceptado
        if($presentacion->estado->nombre == "Aceptado"){
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