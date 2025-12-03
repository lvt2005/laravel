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
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->foreign(['clinic_id'])->references(['id'])->on('clinic')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['patient_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['refund_method_id'])->references(['id'])->on('payment_methods')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['service_id'])->references(['id'])->on('treatment_service')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $table->dropForeign('appointment_schedules_clinic_id_foreign');
            $table->dropForeign('appointment_schedules_doctor_id_foreign');
            $table->dropForeign('appointment_schedules_patient_id_foreign');
            $table->dropForeign('appointment_schedules_payment_method_id_foreign');
            $table->dropForeign('appointment_schedules_refund_method_id_foreign');
            $table->dropForeign('appointment_schedules_service_id_foreign');
        });
    }
};
