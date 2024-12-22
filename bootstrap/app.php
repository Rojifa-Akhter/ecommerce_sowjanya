<?php

use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\UserCheck;
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ADMIN' => AdminCheck::class,
            'USER' => UserCheck::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();