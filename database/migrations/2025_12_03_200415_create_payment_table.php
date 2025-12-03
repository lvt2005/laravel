<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->double('amount')->nullable();
            $table->enum('method', ['CASH', 'BANK_TRANSFER', 'CREDIT_CARD', 'MOMO', 'ZALOPAY', 'VNPAY'])->default('BANK_TRANSFER');
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'FAILED', 'EXPIRED', 'REFUNDED', 'PAID'])->default('PENDING');
            $table->string('transaction_code', 50)->nullable()->index('idx_transaction_code')->comment('Mã giao dịch duy nhất');
            $table->string('qr_code_url', 500)->nullable()->comment('URL mã QR thanh toán');
            $table->text('qr_data')->nullable()->comment('Dữ liệu QR code (JSON)');
            $table->string('bank_transaction_id', 100)->nullable()->index('idx_bank_transaction')->comment('Mã giao dịch ngân hàng');
            $table->dateTime('transaction_time', 6)->nullable();
            $table->dateTime('expires_at')->nullable()->comment('Thời gian hết hạn thanh toán');
            $table->dateTime('verified_at')->nullable()->comment('Thời gian xác minh thanh toán');
            $table->text('metadata')->nullable()->comment('Metadata (JSON)');
            $table->unsignedBigInteger('order_id')->nullable()->unique();
            $table->tinyInteger('is_refund_locked')->default(0)->comment('Khóa không cho hoàn tiền nữa');
            $table->enum('refund_status', ['NONE', 'REQUESTED', 'PROCESSING', 'COMPLETED', 'REJECTED'])->default('NONE');
            $table->tinyInteger('is_payment_locked')->default(0)->comment('Khóa không cho thanh toán lại');

            $table->index(['order_id', 'status'], 'idx_order_status');
            $table->index(['status', 'expires_at'], 'idx_status_expires');
            $table->unique(['transaction_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
