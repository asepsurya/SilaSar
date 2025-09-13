<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RedirectUser;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Foundation\Application;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checkPerusahaan' => \App\Http\Middleware\CheckPerusahaanAndVerified::class,
            'check.auth.perusahaan' => \App\Http\Middleware\CekPerusahaanMiddleware::class,
            'NoCaptcha' => Anhskohbo\NoCaptcha\Facades\NoCaptcha::class,
            'role' => RoleMiddleware::class,
            'redirectIfNotAdmin' => RedirectUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
