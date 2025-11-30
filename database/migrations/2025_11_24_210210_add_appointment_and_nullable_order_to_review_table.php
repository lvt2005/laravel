<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('review', function (Blueprint $table) {
            if (Schema::hasColumn('review','order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropUnique(['order_id']);
                $table->dropColumn('order_id');
            }
            if (!Schema::hasColumn('review','appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('user_id');
                $table->foreign('appointment_id')->references('id')->on('appointment_schedules')->onDelete('cascade');
                $table->unique('appointment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('review', function (Blueprint $table) {
            if (Schema::hasColumn('review','appointment_id')) {
                $table->dropForeign(['appointment_id']);
                $table->dropUnique(['appointment_id']);
                $table->dropColumn('appointment_id');
            }
            $table->unsignedBigInteger('order_id')->nullable();
        });
    }
};

