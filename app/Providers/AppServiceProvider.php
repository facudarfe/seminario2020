<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        /*$this->app->bind('path.public', function() {
            return base_path('public_html');
        });*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        view()->share('descSistema', 'Desarrollado por Facundo Darfe');
        //view()->share('descSistema', 'v2.3 - Desarrollado por Facundo Darfe');
    }
}
