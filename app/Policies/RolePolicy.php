<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
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

    public function manipularRol(User $user, Role $rol){
        $roles = auth()->user()->rolesPermitidos();

        return count($roles) > 0 ? $roles->contains($rol) : false;
    }
}
