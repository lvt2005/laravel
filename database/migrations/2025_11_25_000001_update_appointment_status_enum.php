<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cập nhật enum để thêm pending_confirmation và confirmed
        DB::statement("ALTER TABLE `appointment_schedules` MODIFY COLUMN `status` ENUM('available','booked','pending_confirmation','confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available'");
    }

    public function down(): void
    {
        // Rollback về enum cũ
        DB::statement("ALTER TABLE `appointment_schedules` MODIFY COLUMN `status` ENUM('available','booked','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available'");
    }
};
