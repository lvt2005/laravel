<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `appointment_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `appointment_date` date NOT NULL,
  `time_slot` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_id` int unsigned DEFAULT NULL,
  `clinic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','booked','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appt_sched_doc_date_slot_unique` (`doctor_id`,`appointment_date`,`time_slot`,`start_time`),
  KEY `appointment_schedules_patient_id_index` (`patient_id`),
  KEY `appointment_schedules_clinic_id_index` (`clinic_id`),
  KEY `appointment_schedules_doctor_id_index` (`doctor_id`),
  KEY `appointment_schedules_appointment_date_index` (`appointment_date`),
  KEY `appointment_schedules_doctor_id_appointment_date_index` (`doctor_id`,`appointment_date`),
  CONSTRAINT `appointment_schedules_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointment_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointment_schedules_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_schedules');
    }
};
