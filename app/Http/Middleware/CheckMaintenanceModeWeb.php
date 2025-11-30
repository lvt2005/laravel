<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceModeWeb
{
    /**
     * Routes that are always allowed (even in maintenance mode)
     */
    protected array $allowedRoutes = [
        'dang-nhap',
        'quen-mat-khau',
        'dang-nhap/google-callback',
        'quan-tri',
        'admin',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (SystemSetting::isMaintenanceMode()) {
            $path = trim($request->path(), '/');
            
            // Always allow admin routes
            if ($this->isAdminRoute($path)) {
                return $next($request);
            }
            
            // Always allow certain routes
            if ($this->isAllowedRoute($path)) {
                return $next($request);
            }
            
            // Return maintenance view
            $message = SystemSetting::getMaintenanceMessage();
            return response()->view('maintenance', [
                'message' => $message
            ], 503);
        }
        
        return $next($request);
    }
    
    /**
     * Check if the route is an admin route
     */
    protected function isAdminRoute(string $path): bool
    {
        return str_starts_with($path, 'quan-tri') || str_starts_with($path, 'admin');
    }
    
    /**
     * Check if the route is in the allowed list
     */
    protected function isAllowedRoute(string $path): bool
    {
        foreach ($this->allowedRoutes as $allowedRoute) {
            if ($path === $allowedRoute || str_starts_with($path, $allowedRoute)) {
                return true;
            }
        }
        return false;
    }
}
