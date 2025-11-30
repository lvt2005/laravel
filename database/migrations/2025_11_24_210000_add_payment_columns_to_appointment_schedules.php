<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->decimal('fee_amount', 12, 2)->default(0)->after('notes');
            $table->enum('payment_status', ['UNPAID','PAID','REFUND_PENDING','REFUNDED'])->default('UNPAID')->after('fee_amount');
            $table->unsignedBigInteger('payment_method_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_method_id');
            $table->enum('refund_status', ['NONE','REQUESTED','APPROVED','REJECTED'])->default('NONE')->after('paid_at');
            $table->unsignedBigInteger('refund_method_id')->nullable()->after('refund_status');
            $table->timestamp('refund_requested_at')->nullable()->after('refund_method_id');
            $table->boolean('refund_locked')->default(false)->after('refund_requested_at');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
            $table->foreign('refund_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropForeign(['refund_method_id']);
            $table->dropColumn([
                'fee_amount',
                'payment_status',
                'payment_method_id',
                'paid_at',
                'refund_status',
                'refund_method_id',
                'refund_requested_at',
                'refund_locked'
            ]);
        });
    }
};

