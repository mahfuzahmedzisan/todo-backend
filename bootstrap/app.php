<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifiedUserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['auth:api', 'verified-user'])
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api/v1/private.php'));

            Route::prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api/v1/public.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'verified-user' => VerifiedUserMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
