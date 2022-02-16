<?php

namespace App\Console;

use App\Jobs\NotificacaoMedicamentoJob;
use App\Jobs\OcorrenciasJob;
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
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new OcorrenciasJob,'ocorrencias')->hourlyAt(0);
        $schedule->job(new OcorrenciasJob,'ocorrencias')->hourlyAt(15);
        $schedule->job(new OcorrenciasJob,'ocorrencias')->hourlyAt(30);
        $schedule->job(new OcorrenciasJob,'ocorrencias')->hourlyAt(45);

        $schedule->job(new NotificacaoMedicamentoJob)->hourly();
        $schedule->job(new NotificacaoMedicamentoJob)->hourlyAt(30);


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
