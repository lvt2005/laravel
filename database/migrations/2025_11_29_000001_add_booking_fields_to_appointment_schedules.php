<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('appointment_schedules', 'patient_email')) {
                $table->string('patient_email', 255)->nullable()->after('patient_phone');
            }
            if (!Schema::hasColumn('appointment_schedules', 'is_foreign')) {
                $table->boolean('is_foreign')->default(false)->after('notes');
            }
            if (!Schema::hasColumn('appointment_schedules', 'is_relative')) {
                $table->boolean('is_relative')->default(false)->after('is_foreign');
            }
            if (!Schema::hasColumn('appointment_schedules', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('is_relative');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointment_schedules', function (Blueprint $table) {
            $columns = ['patient_email', 'is_foreign', 'is_relative', 'payment_method'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('appointment_schedules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
