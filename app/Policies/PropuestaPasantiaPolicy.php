<?php

namespace App\Policies;

use App\Models\PropuestaPasantia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropuestaPasantiaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function manipular(User $user, PropuestaPasantia $pasantia){
        return $user->id == $pasantia->docente_id ? true : false;
    }

    public function solicitar(User $user, PropuestaPasantia $pasantia){
        $presentaciones = $user->presentaciones()->whereHas('estado', function($q){
            $q->where('nombre', '!=', 'Rechazado');
        })->get();

        $propuestaTema = $user->propuestaTema()->whereHas('estado', function($q){
            $q->where('nombre', '=', 'Solicitado');
        })->get();

        $propuestasPasantias = $user->propuestasPasantias;
        $estado = $pasantia->estado->nombre;
        
        if(count($propuestaTema) == 0 && count($presentaciones) == 0 && count($propuestasPasantias) == 0 && $estado == 'Disponible'){
            return true;
        }
        else{
            return false;
        }
    }

    public function liberar(User $user, PropuestaPasantia $pasantia){
        if($user->propuestasPasantias->contains($pasantia)){
            return true;
        }
        else
            return false;
    }
}
