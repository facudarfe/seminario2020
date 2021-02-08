<?php

namespace App\Http\Controllers;

use App\Models\Anexo1;
use App\Models\Version_Anexo1;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generarAnexo1(Anexo1 $presentacion, Version_Anexo1 $version){
        if(auth()->user()->can('generarPDF', $presentacion)){
            //Obtenemos la fecha de hoy y la formateamos
            $hoy = Carbon::now();
            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $fecha = sprintf("Salta, %d de %s de %d", $hoy->day, $meses[$hoy->month], $hoy->year);

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('PDF.presentacion', compact('presentacion', 'version', 'fecha', 'meses'));
        
            return $pdf->stream('Anexo1 - TUP.pdf');
        }
        else{
            abort(403);
        }
    }
}
