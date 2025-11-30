<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JwtService;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            $auth = $request->headers->get('authorization') ?? $request->headers->get('Authorization');
            if ($auth && str_starts_with($auth, 'Bearer ')) {
                $token = substr($auth, 7);
            }
        }
        if (!$token) {
            return response()->json(['message' => 'Missing bearer token'], 401);
        }
        try {
            /** @var JwtService $jwtService */
            $jwtService = app(JwtService::class);
            $data = $jwtService->decode($token);
        } catch (\Throwable $e) {
            Log::error('JWT error: '.$e->getMessage());
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }
        try {
            $user = User::find($data['sub'] ?? null);
        } catch (\Throwable $e) {
            Log::error('User lookup error: '.$e->getMessage());
            return response()->json(['message' => 'User lookup failed'],500);
        }
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }
        $request->setUserResolver(fn() => $user);
        $request->attributes->set('session_id', $data['sid'] ?? null);
        return $next($request);
    }
}
