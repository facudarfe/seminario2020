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
}
