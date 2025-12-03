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
        Schema::create('treatment_progress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->dateTime('date', 6)->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('doctor_note')->nullable();
            $table->string('prescription')->nullable();
            $table->string('result')->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_progress');
    }
};
