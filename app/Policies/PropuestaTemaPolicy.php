<?php

namespace App\Policies;

use App\Models\PropuestaTema;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class PropuestaTemaPolicy
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

    public function manipular(User $user, PropuestaTema $tema){
        return $user->id == $tema->docente_id ? true : false;
    }

    public function solicitar(User $user, PropuestaTema $tema){
        $presentaciones = $user->presentaciones()->whereHas('estado', function($q){
                                    $q->where('nombre', '!=', 'Rechazado');
                                })->get();

        $propuestaTema = $user->propuestaTema()->whereHas('estado', function($q){
                                    $q->where('nombre', '=', 'Solicitado');
                                })->get();
        
        if(count($propuestaTema) == 0 && count($presentaciones) == 0 && $tema->alumno == null){
            return true;
        }
        else{
            return false;
        }
    }

    public function crearPresentacion(User $user, PropuestaTema $tema){
        if($tema->alumno != null){
            return $tema->alumno->id == $user->id ? true : false;
        }
        else
            return false;
    }

    public function liberar(User $user, PropuestaTema $tema){
        if($tema->alumno != null){
            return $tema->alumno->id == $user->id;
        }
        else
            return false;
    }
}
