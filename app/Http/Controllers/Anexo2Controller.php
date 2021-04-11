<?php

namespace App\Http\Controllers;

use App\Mail\DefinicionMesaEstudianteMail;
use App\Mail\DefinicionMesaTribunalMail;
use App\Mail\ResultadoMesaMail;
use App\Models\Anexo2;
use App\Models\Estado;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Anexo2Controller extends Controller
{
    public function definirFechaYTribunal(Request $request, Anexo2 $anexo2){
        $request->validate([
            'fecha_definitiva' => ['required', 'date_format:d/m/Y H:i'],
            'tribunalTitular' => ['required', 'array', 'min:1'],
            'tribunalSuplente' => ['required', 'array', 'min:1']
        ]);
        
        try{
            DB::transaction(function () use($request, $anexo2){
                $fecha = str_replace('/', '-', $request->input('fecha_definitiva'));
                $fecha = date('Y-m-d H:i', strtotime($fecha));
                $anexo2->fecha_definitiva = $fecha;
    
                $anexo2->tribunal()->attach($request->tribunalTitular);
                $anexo2->tribunal()->attach($request->tribunalSuplente, ['titular' => false]);
    
                $estado = Estado::where('nombre', 'Fecha y tribunal definidos')->first();
                $anexo2->estado()->associate($estado);
                $anexo2->save();
                
                $presentacion = $anexo2->presentacion;
                $presentacion->estado()->associate($estado);
                $presentacion->save();

                Mail::to($anexo2->presentacion->alumnos)->send(new DefinicionMesaEstudianteMail($anexo2));
                Mail::to($anexo2->tribunal()->where('tribunales_evaluadores.titular', true)->get())->send(new DefinicionMesaTribunalMail($anexo2, true));
                Mail::to($anexo2->tribunal()->where('tribunales_evaluadores.titular', false)->get())->send(new DefinicionMesaTribunalMail($anexo2, false));
            });

            return redirect()->route('presentaciones.inicio')->with('exito', 'Se ha asignado la fecha y el tribunal con éxito');
        }
        catch(Exception $e){
            return redirect()->route('presentaciones.inicio')->withErrors("Ha ocurrido un error. " . $e->getMessage());
        }
    }

    public function show(Anexo2 $anexo2){
        return view('anexos2.ver', compact('anexo2'));
    }

    public function evaluarExamen(Request $request, Anexo2 $anexo2){
        $request->validate([
            'estado' => ['required'],
        ]);

        try{
            DB::transaction(function () use($request, $anexo2) {
                $estado = Estado::find($request->get('estado'));
                $presentacion = $anexo2->presentacion;
    
                $anexo2->estado()->associate($estado);
                $anexo2->devolucion = $request->devolucion;
                $anexo2->save();
    
                if($estado->nombre == 'Aprobado'){
                    $presentacion->estado()->associate($estado);
                    $presentacion->fecha = Carbon::now('America/Argentina/Salta')->format('Y-m-d');
                    $presentacion->devolucion = $request->devolucion;
                }
                else{
                    $presentacion->estado()->associate(Estado::where('nombre', 'Regular')->first());
                }
                $presentacion->save();

                Mail::to($anexo2->presentacion->alumnos)->send(new ResultadoMesaMail($anexo2));
            });

            return redirect()->route('presentaciones.inicio')->with('exito', 'Se realizó la evaluación del examen con éxito.');
        }
        catch(Exception $e){
            return redirect()->route('presentaciones.inicio')->withErrors('Ha ocurrido un error en la evaluación. ' . $e->getMessage());
        }
    }
}
