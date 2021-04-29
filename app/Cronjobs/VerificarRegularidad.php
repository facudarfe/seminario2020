<?php
// Clase para chequear aquellas presentaciones que su estado no cambio a regular en 6 meses y ponerlas como 'No regular'

namespace App\Cronjobs;

use App\Models\Anexo1;
use App\Models\Estado;
use DateTime;

class VerificarRegularidad{

    public function __invoke(){
        $presentaciones = Anexo1::all();
        $fechaHoy = new DateTime(now());

        foreach($presentaciones as $presentacion){
            $fechaAnexo = new DateTime($presentacion->updated_at);
            $diferencia = $fechaAnexo->diff($fechaHoy);
            $meses = ($diferencia->y * 12) + $diferencia->m;
            
            if($presentacion->estado->nombre == 'Aceptado' && $meses >= 6){
                $estado = Estado::where('nombre', 'No regular')->first();
                $presentacion->estado()->associate($estado);
                $presentacion->save();
            }
        }
    }

}