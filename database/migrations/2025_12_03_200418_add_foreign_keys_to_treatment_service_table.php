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
        Schema::table('treatment_service', function (Blueprint $table) {
            $table->foreign(['specialization_id'])->references(['id'])->on('specialization')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_service', function (Blueprint $table) {
            $table->dropForeign('treatment_service_specialization_id_foreign');
        });
    }
};
