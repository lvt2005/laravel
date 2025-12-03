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
        Schema::table('refund_request', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('treatment_order')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['payment_id'])->references(['id'])->on('payment')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['processed_by'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_request', function (Blueprint $table) {
            $table->dropForeign('refund_request_order_id_foreign');
            $table->dropForeign('refund_request_payment_id_foreign');
            $table->dropForeign('refund_request_processed_by_foreign');
            $table->dropForeign('refund_request_user_id_foreign');
        });
    }
};
