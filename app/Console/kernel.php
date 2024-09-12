<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ResetDailyReservations::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Agendar o comando para rodar diariamente às 00:00
        $schedule->command('reservas:reset-daily')->dailyAt('00:00');
    }
}


?>