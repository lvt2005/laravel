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
        Schema::create('treatment_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('specialization_id')->nullable()->index('treatment_service_specialization_id_foreign');
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->string('description')->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->string('benefit1')->nullable();
            $table->string('benefit2')->nullable();
            $table->string('benefit3')->nullable();
            $table->string('benefit4')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('name')->nullable();
            $table->string('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_service');
    }
};
