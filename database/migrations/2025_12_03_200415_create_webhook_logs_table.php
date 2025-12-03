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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('webhook_type', 50)->index('idx_wlog_type');
            $table->text('payload');
            $table->text('headers')->nullable();
            $table->string('ip_address', 45);
            $table->enum('status', ['SUCCESS', 'FAILED', 'PENDING'])->default('PENDING')->index('idx_wlog_status');
            $table->unsignedBigInteger('payment_id')->nullable()->index('idx_wlog_payment');
            $table->text('error_message')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('created_at')->useCurrent()->index('idx_wlog_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
