<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->string('reason', 255);
            $table->text('detail')->nullable();
            $table->enum('status', ['PENDING', 'REVIEWED', 'RESOLVED', 'REJECTED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('forum_post')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('forum_comment')->onDelete('cascade');

            $table->index(['user_id']);
            $table->index(['post_id']);
            $table->index(['comment_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_report');
    }
};
