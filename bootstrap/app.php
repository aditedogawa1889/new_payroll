<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: [
            'latitude',
            'longitude',
            'ip_local',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\LogActivityMiddleware::class,
            \App\Http\Middleware\ForcePasswordChange::class,
        ]);
        $middleware->alias([
            'menu.access' => \App\Http\Middleware\CheckMenuAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
