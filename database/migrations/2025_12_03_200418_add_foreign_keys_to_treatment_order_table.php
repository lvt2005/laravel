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
        Schema::table('treatment_order', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['service_id'])->references(['id'])->on('treatment_service')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_order', function (Blueprint $table) {
            $table->dropForeign('treatment_order_doctor_id_foreign');
            $table->dropForeign('treatment_order_service_id_foreign');
            $table->dropForeign('treatment_order_user_id_foreign');
        });
    }
};
