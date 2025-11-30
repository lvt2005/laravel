<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `system_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_type` enum('boolean','string','integer','json') COLLATE utf8mb4_unicode_ci DEFAULT 'string',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        // Insert default settings
        DB::table('system_settings')->insert([
            // Payment system
            ['setting_key' => 'payment_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Bật/tắt hệ thống thanh toán'],
            
            // Email system
            ['setting_key' => 'email_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Bật/tắt hệ thống gửi email'],
            ['setting_key' => 'email_user_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Gửi email cho người dùng'],
            ['setting_key' => 'email_doctor_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Gửi email cho bác sĩ'],
            
            // Access permissions
            ['setting_key' => 'access_user_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Cho phép bệnh nhân truy cập'],
            ['setting_key' => 'access_doctor_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Cho phép bác sĩ truy cập'],
            
            // Guest booking
            ['setting_key' => 'guest_booking_enabled', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Cho phép đặt lịch không cần đăng nhập'],
            
            // Maintenance mode
            ['setting_key' => 'maintenance_mode', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Chế độ bảo trì hệ thống'],
            ['setting_key' => 'maintenance_message', 'setting_value' => 'Hệ thống đang được bảo trì. Vui lòng quay lại sau.', 'setting_type' => 'string', 'description' => 'Thông báo khi bảo trì'],
            
            // IP blocking
            ['setting_key' => 'blocked_ips', 'setting_value' => '[]', 'setting_type' => 'json', 'description' => 'Danh sách IP bị chặn'],
            
            // Rate limiting
            ['setting_key' => 'rate_limit_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Giới hạn số request'],
            ['setting_key' => 'rate_limit_per_minute', 'setting_value' => '60', 'setting_type' => 'integer', 'description' => 'Số request tối đa mỗi phút'],
            
            // Auto block settings
            ['setting_key' => 'auto_block_failed_login', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Tự động khóa khi đăng nhập sai nhiều lần'],
            ['setting_key' => 'max_failed_login_attempts', 'setting_value' => '5', 'setting_type' => 'integer', 'description' => 'Số lần đăng nhập sai tối đa'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
