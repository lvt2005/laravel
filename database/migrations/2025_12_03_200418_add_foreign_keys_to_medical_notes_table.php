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
        Schema::table('medical_notes', function (Blueprint $table) {
            $table->foreign(['appointment_id'])->references(['id'])->on('appointment_schedules')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['patient_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_notes', function (Blueprint $table) {
            $table->dropForeign('medical_notes_appointment_id_foreign');
            $table->dropForeign('medical_notes_doctor_id_foreign');
            $table->dropForeign('medical_notes_patient_id_foreign');
        });
    }
};
