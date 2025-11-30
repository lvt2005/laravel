<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * Check if user role is allowed to access based on system settings
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return $next($request);
        }

        // Check user access
        if ($user->type === 'USER' && !SystemSetting::isUserAccessEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản bệnh nhân tạm thời bị hạn chế truy cập. Vui lòng liên hệ quản trị viên.',
                'error_code' => 'USER_ACCESS_DISABLED'
            ], 403);
        }

        // Check doctor access
        if ($user->type === 'DOCTOR' && !SystemSetting::isDoctorAccessEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản bác sĩ tạm thời bị hạn chế truy cập. Vui lòng liên hệ quản trị viên.',
                'error_code' => 'DOCTOR_ACCESS_DISABLED'
            ], 403);
        }

        return $next($request);
    }
}
