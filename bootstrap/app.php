<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\TenantJwtMiddleware;
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(CorsMiddleware::class);
        $middleware
            ->group('tenant', [
                \Spatie\Multitenancy\Http\Middleware\NeedsTenant::class,
                \App\Http\Middleware\EnsureTenantIsSet::class,
            ]);
        $middleware->group('auth.jwt.tenant', [TenantJwtMiddleware::class]);
        $middleware->group('auth.jwt', [JwtMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
