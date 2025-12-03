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
        Schema::table('treatment_progress', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('treatment_order')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_progress', function (Blueprint $table) {
            $table->dropForeign('treatment_progress_order_id_foreign');
        });
    }
};
