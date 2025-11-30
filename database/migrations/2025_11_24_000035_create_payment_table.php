<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `payment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `method` enum('CASH','BANK_TRANSFER','CREDIT_CARD','MOMO','ZALOPAY','VNPAY') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BANK_TRANSFER',
  `status` enum('PENDING','PROCESSING','COMPLETED','FAILED','EXPIRED','REFUNDED','PAID') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `transaction_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch duy nhất',
  `qr_code_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL mã QR thanh toán',
  `qr_data` text COLLATE utf8mb4_unicode_ci COMMENT 'Dữ liệu QR code (JSON)',
  `bank_transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã giao dịch ngân hàng',
  `transaction_time` datetime(6) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL COMMENT 'Thời gian hết hạn thanh toán',
  `verified_at` datetime DEFAULT NULL COMMENT 'Thời gian xác minh thanh toán',
  `metadata` text COLLATE utf8mb4_unicode_ci COMMENT 'Metadata (JSON)',
  `order_id` bigint unsigned DEFAULT NULL,
  `is_refund_locked` tinyint NOT NULL DEFAULT '0' COMMENT 'Khóa không cho hoàn tiền nữa',
  `refund_status` enum('NONE','REQUESTED','PROCESSING','COMPLETED','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NONE',
  `is_payment_locked` tinyint NOT NULL DEFAULT '0' COMMENT 'Khóa không cho thanh toán lại',
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_order_id_unique` (`order_id`),
  UNIQUE KEY `payment_transaction_code_unique` (`transaction_code`),
  KEY `idx_transaction_code` (`transaction_code`),
  KEY `idx_status_expires` (`status`,`expires_at`),
  KEY `idx_bank_transaction` (`bank_transaction_id`),
  KEY `idx_order_status` (`order_id`,`status`),
  CONSTRAINT `payment_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `treatment_order` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
