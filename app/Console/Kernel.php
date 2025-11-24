<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule): void
    {
        //  1. Notifikasi 15 menit sebelum peminjaman habis
        $schedule->command('peminjaman:reminder-expiry')->everyMinute();

        //  2. Notifikasi untuk peminjaman terlambat
        $schedule->command('peminjaman:notify-overdue')->everyFiveMinutes();
    }
}
