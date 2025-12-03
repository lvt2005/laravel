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
        Schema::table('work_schedule', function (Blueprint $table) {
            $table->foreign(['doctor_id'])->references(['id'])->on('doctor')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_schedule', function (Blueprint $table) {
            $table->dropForeign('work_schedule_doctor_id_foreign');
        });
    }
};
