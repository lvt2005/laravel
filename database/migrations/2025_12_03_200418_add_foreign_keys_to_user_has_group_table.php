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
        Schema::table('user_has_group', function (Blueprint $table) {
            $table->foreign(['group_id'])->references(['id'])->on('group')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_has_group', function (Blueprint $table) {
            $table->dropForeign('user_has_group_group_id_foreign');
            $table->dropForeign('user_has_group_user_id_foreign');
        });
    }
};
