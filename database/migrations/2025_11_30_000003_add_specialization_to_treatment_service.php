<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatment_service', function (Blueprint $table) {
            $table->unsignedBigInteger('specialization_id')->nullable()->after('id');
            $table->foreign('specialization_id')->references('id')->on('specialization')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('treatment_service', function (Blueprint $table) {
            $table->dropForeign(['specialization_id']);
            $table->dropColumn('specialization_id');
        });
    }
};
