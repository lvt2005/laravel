<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `forum_post` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `comment_count` int NOT NULL DEFAULT '0',
  `like_count` int NOT NULL DEFAULT '0',
  `status` enum('ACTIVE','CLOSED','LOCKED','ARCHIVED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `category` enum('GENERAL_HEALTH','NUTRITION','MENTAL_HEALTH','EXERCISE','DISEASE_TREATMENT','PREVENTION','PREGNANCY','CHILD_HEALTH','ELDERLY_CARE','MEDICINES','OTHER') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GENERAL_HEALTH',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `pin_order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_post_user_id_index` (`user_id`),
  KEY `forum_post_doctor_id_index` (`doctor_id`),
  KEY `forum_post_status_index` (`status`),
  KEY `forum_post_category_index` (`category`),
  KEY `forum_post_is_pinned_index` (`is_pinned`),
  KEY `forum_post_created_at_index` (`created_at`),
  CONSTRAINT `forum_post_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE SET NULL,
  CONSTRAINT `forum_post_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_post');
    }
};
