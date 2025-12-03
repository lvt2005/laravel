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
        Schema::table('role_has_permission', function (Blueprint $table) {
            $table->foreign(['permission_id'])->references(['id'])->on('permission')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['role_id'])->references(['id'])->on('role')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_has_permission', function (Blueprint $table) {
            $table->dropForeign('role_has_permission_permission_id_foreign');
            $table->dropForeign('role_has_permission_role_id_foreign');
        });
    }
};
