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
        Schema::create('token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('token_string')->nullable();
            $table->string('token_type', 50)->default('api');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('expires_at')->nullable()->index('idx_expires_at');
            $table->dateTime('last_used_at')->nullable();
            $table->boolean('is_revoked')->default(false);

            $table->index(['token_string', 'token_type', 'expires_at'], 'idx_token_lookup');
            $table->index(['token_string', 'token_type'], 'idx_token_type');
            $table->index(['user_id', 'token_type'], 'idx_user_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token');
    }
};
