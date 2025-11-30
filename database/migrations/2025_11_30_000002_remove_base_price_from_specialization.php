<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Xóa cột base_price khỏi specialization vì giá tiền sẽ được tính theo dịch vụ
     */
    public function up(): void
    {
        Schema::table('specialization', function (Blueprint $table) {
            if (Schema::hasColumn('specialization', 'base_price')) {
                $table->dropColumn('base_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specialization', function (Blueprint $table) {
            $table->decimal('base_price', 12, 0)->default(0)->after('description')->comment('Giá khám cơ bản');
        });
    }
};
