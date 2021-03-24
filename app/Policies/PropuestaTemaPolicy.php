<?php

namespace App\Policies;

use App\Models\PropuestaTema;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
}
