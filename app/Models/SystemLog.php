<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SystemLog extends Model
{
    protected $table = 'system_logs';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'created_at',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime'
    ];

    /**
     * Relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Log an action
     */
    public static function log(string $action, ?int $userId = null, ?array $metadata = null): self
    {
        return self::create([
            'action' => $action,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            'created_at' => now(),
            'metadata' => $metadata ? json_encode($metadata) : null
        ]);
    }

    /**
     * Log login attempt
     */
    public static function logLogin(int $userId, bool $success = true): self
    {
        return self::log(
            $success ? 'LOGIN_SUCCESS' : 'LOGIN_FAILED',
            $userId,
            ['success' => $success]
        );
    }

    /**
     * Log admin action
     */
    public static function logAdminAction(string $action, int $adminId, array $details = []): self
    {
        return self::log(
            'ADMIN_' . strtoupper($action),
            $adminId,
            $details
        );
    }

    /**
     * Log setting change
     */
    public static function logSettingChange(int $adminId, string $key, $oldValue, $newValue): self
    {
        return self::log(
            'SETTING_CHANGED',
            $adminId,
            [
                'setting_key' => $key,
                'old_value' => $oldValue,
                'new_value' => $newValue
            ]
        );
    }

    /**
     * Get recent logs
     */
    public static function getRecentLogs(int $limit = 50, ?string $action = null)
    {
        $query = self::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit);
        
        if ($action) {
            $query->where('action', 'like', $action . '%');
        }
        
        return $query->get();
    }

    /**
     * Get failed login attempts for an IP
     */
    public static function getFailedLoginAttempts(string $ip, int $minutes = 30): int
    {
        return self::where('action', 'LOGIN_FAILED')
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Get login statistics
     */
    public static function getLoginStats(int $days = 7): array
    {
        return [
            'total_logins' => self::where('action', 'LOGIN_SUCCESS')
                ->where('created_at', '>=', now()->subDays($days))
                ->count(),
            'failed_logins' => self::where('action', 'LOGIN_FAILED')
                ->where('created_at', '>=', now()->subDays($days))
                ->count(),
            'unique_users' => self::where('action', 'LOGIN_SUCCESS')
                ->where('created_at', '>=', now()->subDays($days))
                ->distinct('user_id')
                ->count('user_id')
        ];
    }

    /**
     * Get activity by hour for chart
     */
    public static function getActivityByHour(int $days = 1): array
    {
        $result = [];
        
        $logs = DB::table('system_logs')
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->get();
        
        for ($i = 0; $i < 24; $i++) {
            $result[$i] = 0;
        }
        
        foreach ($logs as $log) {
            $result[$log->hour] = $log->count;
        }
        
        return $result;
    }

    /**
     * Clean old logs
     */
    public static function cleanOldLogs(int $daysToKeep = 90): int
    {
        return self::where('created_at', '<', now()->subDays($daysToKeep))->delete();
    }
}
