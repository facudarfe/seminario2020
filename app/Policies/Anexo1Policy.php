<?php

namespace App\Policies;

use App\Models\Anexo1;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Anexo1Policy
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

    public function resubirVersion(User $user, Anexo1 $anexo){
        if($anexo->estado->nombre == 'Resubir' && $user->id == $anexo->alumno->id){
            return true;
        }
        else{
            return false;
        }
    }

    public function generarPDF(User $user, Anexo1 $anexo){
        $rol = $user->getRoleNames()->first();
        switch($rol){
            case 'Estudiante':
                return $user->id == $anexo->alumno_id;
                break;
            case 'Docente colaborador':
                return $user->id == $anexo->docente_id;
                break;
            case 'Docente responsable': case 'Administrador':
                return true;
                break;
            default:
                return false;
        }
    }
}
