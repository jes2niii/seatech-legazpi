<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (UnauthorizedException $e, \Illuminate\Http\Request $request) {
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            $user = auth()->user();
            if ($user->hasRole('Super Admin')) return redirect()->route('admin.dashboard');
            if ($user->hasRole('Registrar')) return redirect()->route('registrar.dashboard');
            if ($user->hasRole('Training Coordinator')) return redirect()->route('coordinator.dashboard');
            if ($user->hasRole('Instructor')) return redirect()->route('instructor.dashboard');
            return redirect()->route('student.dashboard');
        });
    })->create();
