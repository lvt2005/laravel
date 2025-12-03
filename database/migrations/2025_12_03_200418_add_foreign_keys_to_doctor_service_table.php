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
        Schema::table('doctor_service', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['service_id'])->references(['id'])->on('treatment_service')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_service', function (Blueprint $table) {
            $table->dropForeign('doctor_service_doctor_id_foreign');
            $table->dropForeign('doctor_service_service_id_foreign');
        });
    }
};
