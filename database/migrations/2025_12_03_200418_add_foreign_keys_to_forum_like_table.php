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
        Schema::table('forum_like', function (Blueprint $table) {
            $table->foreign(['comment_id'])->references(['id'])->on('forum_comment')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['post_id'])->references(['id'])->on('forum_post')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_like', function (Blueprint $table) {
            $table->dropForeign('forum_like_comment_id_foreign');
            $table->dropForeign('forum_like_post_id_foreign');
            $table->dropForeign('forum_like_user_id_foreign');
        });
    }
};
