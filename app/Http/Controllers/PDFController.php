<?php

namespace App\Http\Controllers;

use App\Models\Anexo1;
use App\Models\Anexo2;
use App\Models\PropuestaPasantia;
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

    public function generarAnexo2(Anexo2 $anexo2){
        $presentacion = $anexo2->presentacion;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('PDF.anexo2', compact('presentacion', 'anexo2'));
    
        return $pdf->stream('Anexo2 - TUP.pdf');
    }

    public function generarPDFPasantia(PropuestaPasantia $pasantia){
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('PDF.postulantes_pasantia', compact('pasantia'));
    
        return $pdf->stream("Pasantia - $pasantia->lugar.pdf");
    }
}
