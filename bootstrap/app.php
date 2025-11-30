<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// In local/dev CLI sessions, suppress deprecation notices (PHP 8.5) to reduce noise.
if ((getenv('APP_ENV') ?: '${APP_ENV}') === 'local' || PHP_SAPI === 'cli') {
    // Show all errors except deprecations
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: base_path('routes/web.php'),
        api: base_path('routes/api.php'),
        commands: base_path('routes/console.php'),
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add maintenance mode check middleware
        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceModeWeb::class,
        ]);
        
        $middleware->api(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        
        // Register middleware aliases
        $middleware->alias([
            'jwt.custom' => \App\Http\Middleware\JwtMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'ensure.json' => \App\Http\Middleware\EnsureJsonResponse::class,
            'system.check' => \App\Http\Middleware\CheckSystemSettings::class,
            'role.access' => \App\Http\Middleware\CheckRoleAccess::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
