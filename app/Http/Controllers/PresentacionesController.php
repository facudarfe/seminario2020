<?php

namespace App\Http\Controllers;

use App\Mail\AsignarDocenteMail;
use App\Mail\CorreccionMail;
use App\Mail\NuevaPresentacionMail;
use App\Mail\SolicitudMesaDocenteMail;
use App\Mail\SolicitudMesaEstudianteMail;
use App\Models\Anexo1;
use App\Models\Anexo2;
use App\Models\Docente;
use App\Models\Estado;
use App\Models\Modalidad;
use App\Models\PropuestaTema;
use App\Models\User;
use App\Models\Version_Anexo1;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PresentacionesController extends Controller
{
    public function index(){
        $presentaciones = null;
        $presentacionesAsignadas = null;
        $anexos2 = null;

        //Se muestra la tabla con las presentaciones dependiendo del rol
        if(auth()->user()->hasRole('Estudiante')){
            $presentaciones = auth()->user()->presentaciones()->orderByDesc('updated_at')->get();
            $anexos2 = Anexo2::orderByDesc('updated_at')->whereIn('anexo1_id', auth()->user()
            ->presentaciones()->pluck('id')->toArray())->get();
        }
        else{
            if(auth()->user()->hasRole(['Administrador', 'Docente responsable'])){            
                $presentaciones = Anexo1::orderByDesc('updated_at')->get();
                $anexos2 = Anexo2::orderByDesc('updated_at')->get();
            }
            $presentacionesAsignadas = Anexo1::where('docente_id', auth()->user()->id)->orderByDesc('updated_at')->get();
        }

        $solicitado = auth()->user()->propuestaTema()->whereHas('estado', function($q){
                                        $q->where('nombre', '=', 'Solicitado');
                                    })->first();
        
        $docentes = Docente::all();
        $estadosEvaluacion = Estado::where('nombre', 'Aprobado')->orWhere('nombre', 'Desaprobado')->get();

        return view('presentaciones.inicio', compact('presentaciones', 'presentacionesAsignadas', 'solicitado', 'anexos2', 'docentes', 'estadosEvaluacion'));
    }

    public function create(){
        $docentes = Docente::all();
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

        try{
            DB::transaction(function () use($request){
                $encabezado = new Anexo1();
                $version = new Version_Anexo1();
                
                //Codigo para el encabezado
                $encabezado->titulo = $request->titulo;
                
                //Asociar el director y codirector
                $director = Docente::find($request->get('director'));
                $encabezado->director()->associate($director);
                $codirector = Docente::find($request->get('codirector'));
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
            });

            return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha creado la presentacion con exito.');
        }
        catch(Exception $e){
            return redirect(route('presentaciones.inicio'))->with('Ha ocurrido un error: ' . $e->getMessage());
        }
    }

    public function show(Anexo1 $presentacion){
        $docentes = User::role(['Docente responsable', 'Docente colaborador'])->get();
        $estados = Estado::where('nombre', 'Resubir')->orWhere('nombre', 'Rechazado')->orWhere('nombre', 'Aceptado')->get();
        return view('presentaciones.ver', compact('presentacion', 'docentes', 'estados'));
    }

    public function asignarEvaluador(Request $request, Anexo1 $presentacion){
        try{
            DB::transaction(function () use($request, $presentacion){
                $user = User::find($request->get('docente'));
            $presentacion->evaluador()->associate($user);

            $estado = Estado::where('nombre', 'Asignado')->first();
            $presentacion->estado()->associate($estado);

            $presentacion->save();

            //Enviar mail al evaluador
            Mail::to($user->email)->send(new AsignarDocenteMail($presentacion));
            });

            return redirect(route('presentaciones.inicio'))->with('exito', 'Se asignó el evaluador con éxito.');
        }
        catch(Exception $e){
            return redirect(route('presentaciones.inicio'))->withErrors('Ha ocurrido un error: ' . $e->getMessage());
        }
    }

    public function corregirVersion(Request $request){
        //Se obtiene el id de la version a traves del campo oculto 'version'
        $version = Version_Anexo1::find($request->input('version'));

        //Control de que se este por corregir una version Pendiente y que sea el docente correcto asignado
        if($version->estado->nombre == "Pendiente" && $version->anexo->docente_id == auth()->user()->id){
            try{
                DB::transaction(function () use($request, $version){
                    $version->observaciones = $request->observaciones;
                    $version->fecha_correccion = Carbon::now('America/Argentina/Salta')->format('Y-m-d');
                    $estado = Estado::find($request->get('estado'));
                    $version->estado()->associate($estado);
                    
                    $presentacion = $version->anexo;
                    $presentacion->estado()->associate($estado);
        
                    $version->save();
                    $presentacion->save();
        
                    //Enviar mail notificando la corrección
                    Mail::to($version->anexo->alumnos)->send(new CorreccionMail($version));
                });

                return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha realizado la correción exitosamente.');
            }
            catch(Exception $e){
                return redirect(route('presentaciones.inicio'))->withErrors('Ha ocurrido un error: ' . $e->getMessage());
            }
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

        try{
            DB::transaction(function () use($request, $presentacion){
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
            });

            return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha subido una nueva versión de la presentación.');
        }
        catch(Exception $e){
            return redirect(route('presentaciones.inicio'))->withErrors('Ha ocurrido un error: ' . $e->getMessage());
        }
    }

    public function regularizarPresentacion(Request $request, Anexo1 $presentacion){
        //Control de que se este por regularizar una presentacion en estado Aceptado
        if($presentacion->estado->nombre == "Aceptado"){
            try{
                DB::transaction(function () use($presentacion){
                    $estado = Estado::where('nombre', 'Regular')->first();
                    $presentacion->estado()->associate($estado);
        
                    $presentacion->save();
                });

                return redirect(route('presentaciones.inicio'))->with('exito', "Se ha regularizado el trabajo: $presentacion->titulo");
            }
            catch(Exception $e){
                return redirect(route('presentaciones.inicio'))->withErrors('Ha ocurrido un error: ' . $e->getMessage());
            }
        }else{
            abort(403, 'No tienes permisos para regularizar esta presentación.');
        }
    }

    public function aceptarORechazar(Request $request, User $user, Anexo1 $presentacion){
        if($request->tipo == 'aceptar'){
            $pivot = $presentacion->alumnosPendientes()->find($user->id)->pivot;
            $pivot->aceptado = true;
            $pivot->save();

            //Si se le hubiera mandado solicitud para participar en mas de un proyecto se borran todas esas relaciones
            foreach($user->presentacionesPendientes as $pendiente){
                $user->presentacionesPendientes()->detach($pendiente->id);
            }

            return redirect()->route('presentaciones.inicio')->with('exito', 'Has aceptado la participación en el proyecto.
            Ahora aparecerá en la tabla de tus presentaciones.');
        }
        else{
            $user->presentacionesPendientes()->detach($presentacion->id);

            return redirect()->route('presentaciones.inicio')->with('exito', 'Has rechazado la participación en el proyecto.');
        }
    }

    public function proponerFecha(Request $request, Anexo1 $presentacion){
        $request->validate([
            'fecha_propuesta' => ['required', 'date_format:d/m/Y H:i'],
            'informe_final' => ['required', 'file', 'mimes:pdf']
        ]);

        try{
            DB::transaction(function () use($request, $presentacion){
                $anexo2 = new Anexo2();

                $fecha = str_replace('/', '-', $request->input('fecha_propuesta'));
                $fecha = date('Y-m-d H:i', strtotime($fecha));
                $anexo2->fecha_propuesta = $fecha;
                $anexo2->presentacion()->associate($presentacion);
        
                $ruta = $request->file('informe_final')->store('public/informesFinales');
        
                //Guardamos la ruta del archivo en el campo ruta_informe
                $anexo2->ruta_informe = $ruta;
        
                $estado = Estado::where('nombre', 'Fecha propuesta')->first();
                $anexo2->estado()->associate($estado);
                $anexo2->save();
        
                $presentacion->estado()->associate($estado);
                $presentacion->save();
        
                Mail::to($presentacion->alumnos)->send(new SolicitudMesaEstudianteMail($anexo2));
                Mail::to(User::role('Docente responsable')->get())->send(new SolicitudMesaDocenteMail($anexo2));
            });

            return redirect()->route('presentaciones.inicio')
                ->with('exito', 'Has solicitado la mesa examinadora con éxito. En la seccion "Solicitudes mesa examinadora" verás tu solicitud.');
        }
        catch(Exception $e){
            return redirect()->route('presentaciones.inicio')->withErrors('Ha ocurrido un error: ' . $e->getMessage());
        }
    }

    public function solicitarContinuidad(Anexo1 $presentacion){
        $estado = Estado::where('nombre', 'Aceptado')->first();
        $presentacion->estado()->associate($estado);
        $presentacion->save();

        return redirect()->route('presentaciones.inicio');
    }
}