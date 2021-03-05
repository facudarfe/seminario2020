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

    public function crear(User $user){
        $presentaciones = $user->presentaciones()->join('estados', 'anexos1.estado_id', '=', 'estados.id')->whereIn('nombre', ['Pendiente', 'Resubir', 'Aceptado', 'Regular', 'Aprobado'])->get();

        return count($presentaciones)==0 && $user->getRoleNames()->first() == 'Estudiante' ? true : false;
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

    public function subirInforme(User $user, Anexo1 $anexo){
        if($anexo->alumno->id == $user->id && $anexo->estado->nombre == "Aceptado"){
            return true;
        }
        else{
            return false;
        }
    }

    public function mostrar(User $user, Anexo1 $anexo){
        if($user->hasRole(['Administrador', 'Docente responsable']))
            return true;
        elseif($user->hasRole('Estudiante') && $user->id == $anexo->alumno_id)
            return true;
        elseif($user->hasRole('Docente colaborador') && $user->id == $anexo->docente_id)
            return true;
        else
            return false;
    }
}
