<?php

use App\Http\Middleware\CheckNameComplete;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\UserAppLogs;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {

        $schedule->command('model:prune')->dailyAt('22:59');
    })
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'user.logs' => UserAppLogs::class,
            'complete.name' => CheckNameComplete::class
        ]);

        $middleware->group('onboarded', [
            'auth',
            'verified',
            'password.confirm',
            'user.logs',
        ]);

        $middleware->group('pages', [
            'onboarded',
            'complete.name',
            'user.logs'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
