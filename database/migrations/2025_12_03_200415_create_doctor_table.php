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
        Schema::create('doctor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->string('description')->nullable();
            $table->string('degree')->nullable()->comment('Academic degree / title (e.g. Tiến sĩ, Bác sĩ)');
            $table->integer('experience')->nullable();
            $table->double('rating_avg')->nullable();
            $table->unsignedBigInteger('specialization_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->enum('doctor_status', ['ACTIVE', 'INACTIVE', 'NONE'])->nullable();
            $table->unsignedInteger('clinic_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor');
    }
};
