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
        Schema::table('refund_logs', function (Blueprint $table) {
            $table->foreign(['performed_by'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['refund_id'])->references(['id'])->on('refund_request')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_logs', function (Blueprint $table) {
            $table->dropForeign('refund_logs_performed_by_foreign');
            $table->dropForeign('refund_logs_refund_id_foreign');
        });
    }
};
