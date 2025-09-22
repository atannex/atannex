<?php

use App\Http\Middleware\CheckNameComplete;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'complete.name' => CheckNameComplete::class
        ]);

        $middleware->group('onboarded', [
            'auth',
            'verified',
            'password.confirm',
        ]);

        $middleware->group('pages', [
            'onboarded',
            'complete.name',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
