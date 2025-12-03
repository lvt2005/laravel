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
        Schema::create('pharmacy_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('medical_note_id')->nullable()->index();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->string('patient_name')->nullable();
            $table->string('patient_phone', 50)->nullable();
            $table->text('note')->nullable();
            $table->text('medicines')->nullable();
            $table->decimal('total', 12)->default(0);
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_sales');
    }
};
