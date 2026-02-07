<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {

        $schedule->command(
            'queue:work database --stop-when-empty --sleep=3 --tries=3 --max-time=55'
        )
            ->everyMinute()
            ->withoutOverlapping()
            ->onOneServer();

        $schedule->command('atannex:breaking-cleanup')
            ->everyMinute()
            ->withoutOverlapping()
            ->onOneServer();

        $schedule->command('atannex:update-editor-picks')
            ->everyMinute()
            ->withoutOverlapping()
            ->onOneServer();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // No Sanctum middleware registered
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
