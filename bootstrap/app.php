<?php

use App\Http\Middleware\LoggedInUserData;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(LoggedInUserData::class);
        $middleware->validateCsrfTokens(except: ['*']);
        $middleware->trustProxies(at: [
            '10.99.1.16', // nginx.i.bolkhuis.nl
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
