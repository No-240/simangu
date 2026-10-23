<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Pengaturan redirect otomatis berdasarkan role user[cite: 18]
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(fn (Request $request) => match ($request->user()?->role) {
            'admin' => '/admin/inventory',
            'staff' => '/staff/inventory',
            default => '/login',
        });

        // Mendaftarkan alias middleware role[cite: 17, 18]
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();