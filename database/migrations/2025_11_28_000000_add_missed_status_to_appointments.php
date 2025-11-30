<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'missed' to the enum
        DB::statement("ALTER TABLE `appointment_schedules` MODIFY COLUMN `status` ENUM('available','booked','pending_confirmation','confirmed','completed','cancelled','missed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available'");
    }

    public function down(): void
    {
        // Revert (careful if data exists)
        DB::statement("ALTER TABLE `appointment_schedules` MODIFY COLUMN `status` ENUM('available','booked','pending_confirmation','confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available'");
    }
};
