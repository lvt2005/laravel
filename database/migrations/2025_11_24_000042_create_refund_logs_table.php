<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `refund_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `refund_id` bigint unsigned NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_before` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_after` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `performed_by` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `metadata` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_rlog_refund` (`refund_id`),
  KEY `idx_rlog_action` (`action`),
  KEY `idx_rlog_created_at` (`created_at`),
  KEY `refund_logs_performed_by_index` (`performed_by`),
  CONSTRAINT `refund_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refund_logs_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `refund_request` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_logs');
    }
};
