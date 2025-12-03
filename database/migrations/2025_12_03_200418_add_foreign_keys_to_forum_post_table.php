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
        Schema::table('forum_post', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_post', function (Blueprint $table) {
            $table->dropForeign('forum_post_doctor_id_foreign');
            $table->dropForeign('forum_post_user_id_foreign');
        });
    }
};
