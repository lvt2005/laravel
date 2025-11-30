<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `forum_comment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `parent_comment_id` bigint unsigned DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `like_count` int NOT NULL DEFAULT '0',
  `is_answer` tinyint(1) NOT NULL DEFAULT '0',
  `is_helpful` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_comment_post_id_index` (`post_id`),
  KEY `forum_comment_user_id_index` (`user_id`),
  KEY `forum_comment_doctor_id_index` (`doctor_id`),
  KEY `forum_comment_parent_comment_id_index` (`parent_comment_id`),
  KEY `forum_comment_is_answer_index` (`is_answer`),
  KEY `forum_comment_created_at_index` (`created_at`),
  CONSTRAINT `forum_comment_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE SET NULL,
  CONSTRAINT `forum_comment_parent_comment_id_foreign` FOREIGN KEY (`parent_comment_id`) REFERENCES `forum_comment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_comment_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_post` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_comment_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_comment');
    }
};
