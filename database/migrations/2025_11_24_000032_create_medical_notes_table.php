<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `medical_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinical_history` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `chief_complaint` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `physical_examination` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment_plan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `visit_date` date NOT NULL,
  `visit_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'routine',
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `blood_pressure_systolic` int DEFAULT NULL,
  `blood_pressure_diastolic` int DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `heart_rate` int DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_notes_doctor_id_index` (`doctor_id`),
  KEY `medical_notes_patient_id_index` (`patient_id`),
  KEY `medical_notes_appointment_id_index` (`appointment_id`),
  KEY `medical_notes_doctor_id_patient_id_index` (`doctor_id`,`patient_id`),
  KEY `medical_notes_visit_date_index` (`visit_date`),
  FULLTEXT KEY `medical_notes_chief_complaint_diagnosis_notes_fulltext` (`chief_complaint`,`diagnosis`,`notes`),
  CONSTRAINT `medical_notes_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointment_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_notes_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_notes');
    }
};
