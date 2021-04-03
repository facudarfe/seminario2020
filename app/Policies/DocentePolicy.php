<?php

namespace App\Policies;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocentePolicy
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

    public function editarOEliminar(User $user, Docente $docente){
        $user = User::where('dni', $docente->dni)->first();

        return $user == null;
    }
}
