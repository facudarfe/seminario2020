<?php

namespace App\Providers;

use App\Models\Anexo1;
use App\Models\Docente;
use App\Models\PropuestaPasantia;
use App\Models\PropuestaTema;
use App\Models\User;
use App\Policies\Anexo1Policy;
use App\Policies\DocentePolicy;
use App\Policies\PropuestaPasantiaPolicy;
use App\Policies\PropuestaTemaPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Anexo1::class => Anexo1Policy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
        PropuestaTema::class => PropuestaTemaPolicy::class,
        PropuestaPasantia::class => PropuestaPasantiaPolicy::class,
        Docente::class => DocentePolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Dar todos los permisos al rol Administrador
        Gate::after(function ($user, $ability) {
            return $user->hasRole('Administrador');
        });
    }
}
