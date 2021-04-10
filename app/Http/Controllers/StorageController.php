<?php

namespace App\Http\Controllers;

use App\Models\Anexo1;
use App\Models\Anexo2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function guardarInforme(Request $request, Anexo1 $presentacion){
        $request->validate([
            'informe' => ['file', 'mimes:pdf']
        ]);

        //Si ya habia un archivo subido se borra antes de subir el proximo
        if($presentacion->ruta_informe){
            Storage::delete($presentacion->ruta_informe);
        }

        $ruta = $request->file('informe')->store('public/informes');

        //Guardamos la ruta del archivo en el campo ruta_informe
        $presentacion->ruta_informe = $ruta;
        $presentacion->save();
        
        return redirect(route('presentaciones.inicio'))->with('exito', 'Se ha subido el informe correctamente.');
    }

    public function descargarInforme(Anexo1 $presentacion){
        return Storage::download($presentacion->ruta_informe);
    }

    public function descargarInformeFinal(Anexo2 $anexo2){
        return Storage::download($anexo2->ruta_informe);
    }
}
