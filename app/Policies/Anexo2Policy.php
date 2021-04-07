<?php

namespace App\Policies;

use App\Models\Anexo2;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Anexo2Policy
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

    public function generarPDF(User $user, Anexo2 $anexo2){
        $rol = $user->getRoleNames()->first();

        switch($rol){
            case 'Administrador': case 'Docente responsable':
                return true;
            case 'Estudiante':
                $presentacion = $anexo2->presentacion;
                if($user->presentaciones->contains($presentacion))
                    return true;
                else
                    return false;
            default: 
                return false;
        }
    }
}
