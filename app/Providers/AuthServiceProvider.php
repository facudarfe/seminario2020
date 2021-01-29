<?php

namespace App\Providers;

use App\Models\Anexo1;
use App\Policies\Anexo1Policy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Anexo1::class, Anexo1Policy::class
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
