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
        Schema::table('doctor', function (Blueprint $table) {
            $table->foreign(['clinic_id'])->references(['id'])->on('clinic')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['specialization_id'])->references(['id'])->on('specialization')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor', function (Blueprint $table) {
            $table->dropForeign('doctor_clinic_id_foreign');
            $table->dropForeign('doctor_specialization_id_foreign');
            $table->dropForeign('doctor_user_id_foreign');
        });
    }
};
