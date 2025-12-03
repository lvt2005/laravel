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
        Schema::create('appointment_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id')->index();
            $table->date('appointment_date')->index();
            $table->string('time_slot', 20);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->string('patient_name')->nullable();
            $table->string('patient_phone')->nullable();
            $table->string('patient_email')->nullable();
            $table->unsignedInteger('clinic_id')->nullable()->index();
            $table->unsignedBigInteger('service_id')->nullable()->index('appointment_schedules_service_id_foreign');
            $table->string('service_name')->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('room_number')->nullable();
            $table->enum('status', ['available', 'booked', 'pending_confirmation', 'confirmed', 'completed', 'cancelled', 'missed'])->default('available');
            $table->text('notes')->nullable();
            $table->boolean('is_foreign')->default(false);
            $table->boolean('is_relative')->default(false);
            $table->string('payment_method', 50)->nullable();
            $table->decimal('fee_amount', 12)->default(0);
            $table->enum('payment_status', ['UNPAID', 'PENDING_APPROVAL', 'PAID', 'REFUND_PENDING', 'REFUNDED'])->nullable()->default('UNPAID');
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('appointment_schedules_payment_method_id_foreign');
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_id', 50)->nullable();
            $table->enum('refund_status', ['NONE', 'REQUESTED', 'PROCESSING', 'APPROVED', 'REJECTED', 'COMPLETED'])->nullable()->default('NONE');
            $table->unsignedBigInteger('refund_method_id')->nullable()->index('appointment_schedules_refund_method_id_foreign');
            $table->timestamp('refund_requested_at')->nullable();
            $table->boolean('refund_locked')->default(false);
            $table->string('refund_otp', 6)->nullable();
            $table->timestamp('refund_otp_expires_at')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamp('refund_completed_at')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'appointment_date']);
            $table->unique(['doctor_id', 'appointment_date', 'time_slot', 'start_time'], 'appt_sched_doc_date_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_schedules');
    }
};
