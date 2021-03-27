<?php

namespace App\Console;

use App\Models\Estado;
use App\Models\PropuestaPasantia;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        //Cambiar el estado de las pasantias a "Cerrado" cuando llegue la fecha de fin
        $schedule->call(function(){
            $pasantias = PropuestaPasantia::whereHas('estado', function($q){
                $q->where('nombre', 'Disponible');
            })->get();

            foreach($pasantias as $pasantia){
                $estado = Estado::where('nombre', 'Cerrado')->first();
                $today = date('d/m/Y', strtotime(Carbon::now()->toDateString()));
                if($pasantia->fecha_fin == $today){
                    $pasantia->estado()->associate($estado);
                    $pasantia->save();
                }
            }
        })->timezone('America/Argentina/Salta')->dailyAt('23:59');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
