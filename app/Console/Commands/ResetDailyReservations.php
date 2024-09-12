<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Models\Reservas;
use Carbon\Carbon;

class ResetDailyReservations extends Command
{
    protected $signature = 'reservas:reset';
    protected $description = 'Reseta as reservas diárias mantendo os check-ins semanais';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Reseta as reservas do dia
        Reservas::whereDate('created_at', Carbon::today())->delete();

        $this->info('Reservas diárias resetadas com sucesso.');
    }
}

