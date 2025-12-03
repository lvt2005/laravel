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
        Schema::create('refund_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('refund_id')->index('idx_rlog_refund');
            $table->string('action', 100)->index('idx_rlog_action');
            $table->string('status_before', 20)->nullable();
            $table->string('status_after', 20)->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->dateTime('created_at')->useCurrent()->index('idx_rlog_created_at');
            $table->text('metadata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_logs');
    }
};
