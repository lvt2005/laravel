<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify payment_status enum to add PENDING_APPROVAL
        DB::statement("ALTER TABLE appointment_schedules MODIFY payment_status ENUM('UNPAID','PENDING_APPROVAL','PAID','REFUND_PENDING','REFUNDED') DEFAULT 'UNPAID'");
        
        // Modify refund_status enum to add more states
        DB::statement("ALTER TABLE appointment_schedules MODIFY refund_status ENUM('NONE','REQUESTED','PROCESSING','APPROVED','REJECTED','COMPLETED') DEFAULT 'NONE'");
        
        // Add refund OTP columns
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->string('refund_otp', 6)->nullable()->after('refund_locked');
            $table->timestamp('refund_otp_expires_at')->nullable()->after('refund_otp');
            $table->text('refund_reason')->nullable()->after('refund_otp_expires_at');
            $table->timestamp('refund_completed_at')->nullable()->after('refund_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->dropColumn(['refund_otp', 'refund_otp_expires_at', 'refund_reason', 'refund_completed_at']);
        });
        
        DB::statement("ALTER TABLE appointment_schedules MODIFY payment_status ENUM('UNPAID','PAID','REFUND_PENDING','REFUNDED') DEFAULT 'UNPAID'");
        DB::statement("ALTER TABLE appointment_schedules MODIFY refund_status ENUM('NONE','REQUESTED','APPROVED','REJECTED') DEFAULT 'NONE'");
    }
};
