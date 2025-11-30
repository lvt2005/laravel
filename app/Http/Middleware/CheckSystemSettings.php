<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\SystemLog;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemSettings
{
    /**
     * Handle an incoming request.
     *
     * Check system settings like maintenance mode, IP blocking, etc.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if IP is blocked
        $clientIp = $request->ip();
        if (SystemSetting::isIpBlocked($clientIp)) {
            SystemLog::log('BLOCKED_IP_ACCESS', null, ['ip' => $clientIp, 'path' => $request->path()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Địa chỉ IP của bạn đã bị chặn. Vui lòng liên hệ quản trị viên.',
                'error_code' => 'IP_BLOCKED'
            ], 403);
        }

        // Check maintenance mode (skip for admin routes)
        if (!$request->is('api/admin/*') && !$request->is('api/auth/*')) {
            if (SystemSetting::isMaintenanceMode()) {
                // Allow admin users to bypass maintenance
                $user = $request->user();
                $isAdmin = $user && $user->type === 'ADMIN';
                
                if (!$isAdmin) {
                    return response()->json([
                        'success' => false,
                        'message' => SystemSetting::getMaintenanceMessage(),
                        'error_code' => 'MAINTENANCE_MODE'
                    ], 503);
                }
            }
        }

        return $next($request);
    }
}
