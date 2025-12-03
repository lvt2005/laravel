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
        Schema::create('forum_post', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('doctor_id')->nullable()->index();
            $table->string('title');
            $table->longText('content');
            $table->integer('view_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->enum('status', ['ACTIVE', 'CLOSED', 'LOCKED', 'ARCHIVED'])->default('ACTIVE')->index();
            $table->enum('category', ['GENERAL_HEALTH', 'NUTRITION', 'MENTAL_HEALTH', 'EXERCISE', 'DISEASE_TREATMENT', 'PREVENTION', 'PREGNANCY', 'CHILD_HEALTH', 'ELDERLY_CARE', 'MEDICINES', 'OTHER'])->default('GENERAL_HEALTH')->index();
            $table->boolean('is_pinned')->default(false)->index();
            $table->integer('pin_order')->nullable();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_post');
    }
};
