<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $table = 'system_settings';
    
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description'
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = 'system_setting_' . $key;
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('setting_key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->setting_value, $setting->setting_type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = null): bool
    {
        $setting = self::where('setting_key', $key)->first();
        
        if ($setting) {
            $setting->setting_value = is_array($value) ? json_encode($value) : (string) $value;
            if ($type) {
                $setting->setting_type = $type;
            }
            $result = $setting->save();
        } else {
            $result = self::create([
                'setting_key' => $key,
                'setting_value' => is_array($value) ? json_encode($value) : (string) $value,
                'setting_type' => $type ?? 'string'
            ]) ? true : false;
        }
        
        // Clear cache
        Cache::forget('system_setting_' . $key);
        Cache::forget('all_system_settings');
        
        return $result;
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('all_system_settings', 3600, function () {
            $settings = self::all();
            $result = [];
            
            foreach ($settings as $setting) {
                $result[$setting->setting_key] = self::castValue($setting->setting_value, $setting->setting_type);
            }
            
            return $result;
        });
    }

    /**
     * Update multiple settings at once
     */
    public static function updateBatch(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
        
        return true;
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value || $value === 'true' || $value === '1';
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true) ?? [];
            default:
                return $value;
        }
    }

    /**
     * Check if payment is enabled
     */
    public static function isPaymentEnabled(): bool
    {
        return self::get('payment_enabled', true);
    }

    /**
     * Check if email is enabled
     */
    public static function isEmailEnabled(): bool
    {
        return self::get('email_enabled', true);
    }

    /**
     * Check if user email is enabled
     */
    public static function isUserEmailEnabled(): bool
    {
        return self::isEmailEnabled() && self::get('email_user_enabled', true);
    }

    /**
     * Check if doctor email is enabled
     */
    public static function isDoctorEmailEnabled(): bool
    {
        return self::isEmailEnabled() && self::get('email_doctor_enabled', true);
    }

    /**
     * Check if guest booking is enabled
     */
    public static function isGuestBookingEnabled(): bool
    {
        return self::get('guest_booking_enabled', false);
    }

    /**
     * Check if maintenance mode is on
     */
    public static function isMaintenanceMode(): bool
    {
        return self::get('maintenance_mode', false);
    }

    /**
     * Get maintenance message
     */
    public static function getMaintenanceMessage(): string
    {
        return self::get('maintenance_message', 'Hệ thống đang được bảo trì. Vui lòng quay lại sau.');
    }

    /**
     * Check if user access is enabled
     */
    public static function isUserAccessEnabled(): bool
    {
        return self::get('access_user_enabled', true);
    }

    /**
     * Check if doctor access is enabled
     */
    public static function isDoctorAccessEnabled(): bool
    {
        return self::get('access_doctor_enabled', true);
    }

    /**
     * Get blocked IPs
     */
    public static function getBlockedIps(): array
    {
        return self::get('blocked_ips', []);
    }

    /**
     * Check if an IP is blocked
     */
    public static function isIpBlocked(string $ip): bool
    {
        $blockedIps = self::getBlockedIps();
        return in_array($ip, $blockedIps);
    }

    /**
     * Block an IP
     */
    public static function blockIp(string $ip): bool
    {
        $blockedIps = self::getBlockedIps();
        if (!in_array($ip, $blockedIps)) {
            $blockedIps[] = $ip;
            return self::set('blocked_ips', $blockedIps, 'json');
        }
        return true;
    }

    /**
     * Unblock an IP
     */
    public static function unblockIp(string $ip): bool
    {
        $blockedIps = self::getBlockedIps();
        $blockedIps = array_filter($blockedIps, fn($blocked) => $blocked !== $ip);
        return self::set('blocked_ips', array_values($blockedIps), 'json');
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('all_system_settings');
        
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget('system_setting_' . $setting->setting_key);
        }
    }
}
