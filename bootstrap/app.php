<?php

use App\Http\Middleware\CheckNameComplete;
use App\Http\Middleware\TrackUserActivity;
use Illuminate\Console\Scheduling\Schedule;
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
        //
    })
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            'track.activity' => TrackUserActivity::class,
            'complete.name'  => CheckNameComplete::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Group: authenticated
        |--------------------------------------------------------------------------
        */
        $middleware->group('authenticated', [
            'auth',
            'track.activity',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Group: onboarded
        |--------------------------------------------------------------------------
        | Applied AFTER user registers / logs in
        |--------------------------------------------------------------------------
        */
        $middleware->group('onboarded', [
            'authenticated',
            'verified',
            'password.confirm',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Group: pages
        |--------------------------------------------------------------------------
        | Final step – profile must be complete
        |--------------------------------------------------------------------------
        */
        $middleware->group('pages', [
            'onboarded',
            'complete.name',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
