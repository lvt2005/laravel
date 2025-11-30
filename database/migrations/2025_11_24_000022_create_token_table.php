<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `token` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_string` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'api',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime DEFAULT NULL,
  `last_used_at` datetime DEFAULT NULL,
  `is_revoked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_user_type` (`user_id`,`token_type`),
  KEY `idx_expires_at` (`expires_at`),
  KEY `idx_token_type` (`token_string`,`token_type`),
  KEY `idx_token_lookup` (`token_string`,`token_type`,`expires_at`),
  CONSTRAINT `token_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('token');
    }
};
