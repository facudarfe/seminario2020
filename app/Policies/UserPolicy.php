<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    /**
     * Permiso para determinar si un usuario puede gestionar a otro usuario (Crear, editar, eliminar)
     */
    public function gestionar(User $user, User $userEdit){
        $rolesPermitidos = $user->rolesPermitidos();
        $rol = $userEdit->roles->first();

        return empty($rolesPermitidos) ? [] : $rolesPermitidos->contains($rol);
    }
}
