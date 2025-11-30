<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `doctor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` int DEFAULT NULL,
  `rating_avg` double DEFAULT NULL,
  `specialization_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `doctor_status` enum('ACTIVE','INACTIVE','NONE') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doctor_user_id_unique` (`user_id`),
  KEY `doctor_specialization_id_index` (`specialization_id`),
  KEY `doctor_clinic_id_index` (`clinic_id`),
  CONSTRAINT `doctor_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`) ON DELETE SET NULL,
  CONSTRAINT `doctor_specialization_id_foreign` FOREIGN KEY (`specialization_id`) REFERENCES `specialization` (`id`) ON DELETE SET NULL,
  CONSTRAINT `doctor_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor');
    }
};
