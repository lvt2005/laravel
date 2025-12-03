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
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar_url')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('full_name')->nullable();
            $table->enum('gender', ['FEMALE', 'MALE', 'OTHER'])->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'NONE'])->nullable();
            $table->enum('type', ['ADMIN', 'DOCTOR', 'USER'])->nullable();
            $table->dateTime('last_login')->nullable();
            $table->integer('login_count')->default(0);
            $table->boolean('two_factor_enabled')->default(false);
            $table->boolean('email_notification')->default(true);
            $table->boolean('reply_notification')->default(true);
            $table->unsignedTinyInteger('failed_login_attempts')->default(0);
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_failed_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
