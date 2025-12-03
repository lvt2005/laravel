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
        Schema::create('verification_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('email', 100)->index();
            $table->dateTime('expires_at');
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_active')->nullable();
            $table->longText('metadata')->nullable();
            $table->string('token_hash');
            $table->enum('token_type', ['ACCESS', 'API', 'BOOKING', 'EMAIL_VERIFY', 'PAYMENT', 'RESET_PASSWORD', '_2FA'])->index();
            $table->dateTime('used_at')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('verification_code')->nullable();
            $table->unsignedBigInteger('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_tokens');
    }
};
