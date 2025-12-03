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
        Schema::create('medical_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doctor_id')->index();
            $table->unsignedBigInteger('patient_id')->index();
            $table->unsignedBigInteger('appointment_id')->nullable()->index();
            $table->string('patient_name');
            $table->string('patient_phone')->nullable();
            $table->string('patient_email')->nullable();
            $table->longText('clinical_history');
            $table->longText('chief_complaint');
            $table->longText('physical_examination');
            $table->longText('diagnosis');
            $table->longText('treatment_plan');
            $table->longText('notes')->nullable();
            $table->date('visit_date')->index();
            $table->string('visit_type')->default('routine');
            $table->decimal('weight', 5)->nullable();
            $table->decimal('height', 5)->nullable();
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->decimal('temperature', 4)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('sent_to_patient')->default(false);
            $table->boolean('sent_to_pharmacy')->default(false);
            $table->string('pharmacy_status', 50)->nullable()->default('pending');
            $table->timestamp('pharmacy_processed_at')->nullable();
            $table->text('medicine_details')->nullable();
            $table->decimal('medicine_total', 12)->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->fullText(['chief_complaint', 'diagnosis', 'notes']);
            $table->index(['doctor_id', 'patient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_notes');
    }
};
