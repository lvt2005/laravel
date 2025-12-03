<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Routes that are always allowed (even in maintenance mode)
     */
    protected array $allowedRoutes = [
        'api/auth/login',
        'api/auth/logout',
        'api/auth/verify-2fa',
        'api/auth/google/callback',
        'api/admin/*',
        'api/profile/me', // Allow checking own profile
        'api/public/maintenance-status', // Allow checking maintenance status
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (SystemSetting::isMaintenanceMode()) {
            $path = $request->path();
            
            // Always allow admin routes
            if ($this->isAdminRoute($path)) {
                return $next($request);
            }
            
            // Always allow certain auth routes
            if ($this->isAllowedRoute($path)) {
                return $next($request);
            }
            
            // Check if user is admin (if authenticated)
            $user = $request->user();
            if ($user && $user->type === 'ADMIN') {
                return $next($request);
            }
            
            // Return maintenance message for everyone else
            $message = SystemSetting::getMaintenanceMessage();
            return response()->json([
                'error' => 'MAINTENANCE_MODE',
                'message' => $message,
                'maintenance' => true
            ], 503);
        }
        
        return $next($request);
    }
    
    /**
     * Check if the route is an admin route
     */
    protected function isAdminRoute(string $path): bool
    {
        return str_starts_with($path, 'api/admin');
    }
    
    /**
     * Check if the route is in the allowed list
     */
    protected function isAllowedRoute(string $path): bool
    {
        foreach ($this->allowedRoutes as $allowedRoute) {
            // Handle wildcard routes
            if (str_ends_with($allowedRoute, '*')) {
                $prefix = rtrim($allowedRoute, '*');
                if (str_starts_with($path, $prefix)) {
                    return true;
                }
            } else if ($path === $allowedRoute) {
                return true;
            }
        }
        return false;
    }
}
