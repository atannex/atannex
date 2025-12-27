<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckNameComplete;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /* -------------------------------------------------
     | Scheduled Tasks (Laravel 11 style)
     |--------------------------------------------------*/
    ->withSchedule(function (Schedule $schedule) {

        $schedule->command(
            'queue:work database --stop-when-empty --sleep=3 --tries=3 --max-time=55'
        )
            ->everyMinute()
            ->withoutOverlapping()
            ->onOneServer();
    })

    ->withMiddleware(function (Middleware $middleware): void {
        //
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
