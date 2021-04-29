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
        $presentacionesNoRechazadas = $user->presentaciones()->whereDoesntHave('estado', function($q){
            $q->where('nombre', 'Rechazado');
        })->get();

        return count($presentacionesNoRechazadas)==0 && $user->getRoleNames()->first() == 'Estudiante';
    }

    public function resubirVersion(User $user, Anexo1 $anexo){
        if($anexo->estado->nombre == 'Resubir' && $user->presentaciones->contains($anexo)){
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
                return $user->presentaciones->contains($anexo);
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
        if($user->presentaciones->contains($anexo) && $anexo->estado->nombre == "Aceptado"){
            return true;
        }
        else{
            return false;
        }
    }

    public function mostrar(User $user, Anexo1 $anexo){
        if($user->hasRole(['Administrador', 'Docente responsable']))
            return true;
        elseif($user->hasRole('Estudiante') && ($user->presentaciones->contains($anexo) || 
                $user->presentacionesPendientes->contains($anexo)))
            return true;
        elseif($user->hasRole('Docente colaborador') && $user->id == $anexo->docente_id)
            return true;
        else
            return false;
    }

    public function aceptarORechazar(User $user, Anexo1 $anexo){
        return $anexo->alumnosPendientes->contains($user);
    }

    public function proponerFecha(User $user, Anexo1 $presentacion){
        return $user->getRoleNames()->first() == 'Estudiante' && $user->presentaciones->contains($presentacion) 
                && $presentacion->estado->nombre == 'Regular';
    }

    public function subirCodigoFuente(User $user, Anexo1 $presentacion){
        return $user->presentaciones->contains($presentacion) && $presentacion->estado->nombre == 'Aprobado';
    }

    public function solicitarContinuidad(User $user, Anexo1 $presentacion){
        return $user->presentaciones->contains($presentacion) && $presentacion->estado->nombre == 'No regular';
    }
}
