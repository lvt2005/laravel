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
        Schema::table('review', function (Blueprint $table) {
            $table->foreign(['appointment_id'])->references(['id'])->on('appointment_schedules')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review', function (Blueprint $table) {
            $table->dropForeign('review_appointment_id_foreign');
            $table->dropForeign('review_doctor_id_foreign');
            $table->dropForeign('review_user_id_foreign');
        });
    }
};
