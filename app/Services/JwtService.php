<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtService
{
    private string $secret;
    private int $accessTtlSeconds;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET', config('app.key'));
        $this->accessTtlSeconds = 15 * 60; // 15 minutes
    }

    public function createAccessToken(User $user, string $sessionId): string
    {
        $now = time();
        $payload = [
            'sub' => $user->id,
            'sid' => $sessionId,
            'type' => $user->type,
            'iat' => $now,
            'exp' => $now + $this->accessTtlSeconds,
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
