<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'email_domain' => \App\Http\Middleware\EmailDomainMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Pengingat 15 menit sebelum waktu peminjaman habis.
        $schedule->command('peminjaman:reminder-expiry')->everyMinute();
        // Notifikasi petugas untuk peminjaman terlambat.
        $schedule->command('peminjaman:notify-overdue')->everyFiveMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
