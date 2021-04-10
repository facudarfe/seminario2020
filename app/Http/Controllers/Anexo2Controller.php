<?php

namespace App\Http\Controllers;

use App\Models\Anexo2;
use App\Models\Estado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
