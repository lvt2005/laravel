<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // minimal global middleware
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            // Removed session & cookies & csrf for debug
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\CheckMaintenanceModeWeb::class,
        ],
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ],
    ];

    protected $middlewareAliases = [
        'jwt.custom' => \App\Http\Middleware\JwtMiddleware::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'ensure.json' => \App\Http\Middleware\EnsureJsonResponse::class,
        'system.check' => \App\Http\Middleware\CheckSystemSettings::class,
        'role.access' => \App\Http\Middleware\CheckRoleAccess::class,
        'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
    ];
}
