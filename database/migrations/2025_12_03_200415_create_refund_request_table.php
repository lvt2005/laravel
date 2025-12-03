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
        Schema::create('refund_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id')->unique();
            $table->unsignedBigInteger('order_id')->index('idx_refund_order');
            $table->unsignedBigInteger('user_id')->index('idx_refund_user');
            $table->decimal('amount', 10);
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('account_number', 50);
            $table->text('reason');
            $table->enum('status', ['PENDING', 'PROCESSING', 'APPROVED', 'REJECTED', 'COMPLETED', 'CANCELLED'])->default('PENDING')->index('idx_refund_status');
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable()->index('refund_request_processed_by_foreign');
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('created_at')->useCurrent()->index('idx_refund_created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_request');
    }
};
