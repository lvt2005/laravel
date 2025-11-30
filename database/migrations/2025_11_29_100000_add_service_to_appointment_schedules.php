<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('appointment_schedules', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->after('clinic_id');
                $table->foreign('service_id')
                      ->references('id')
                      ->on('treatment_service')
                      ->onDelete('set null');
            }
            if (!Schema::hasColumn('appointment_schedules', 'service_name')) {
                $table->string('service_name', 255)->nullable()->after('service_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('appointment_schedules', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropColumn('service_id');
            }
            if (Schema::hasColumn('appointment_schedules', 'service_name')) {
                $table->dropColumn('service_name');
            }
        });
    }
};
