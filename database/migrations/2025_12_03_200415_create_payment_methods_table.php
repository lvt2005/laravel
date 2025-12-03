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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('payment_methods_user_id_foreign');
            $table->string('method', 50);
            $table->string('method_name')->nullable();
            $table->string('masked_detail')->nullable();
            $table->string('card_number', 32)->nullable();
            $table->string('card_holder', 100)->nullable();
            $table->string('expiry_month', 2)->nullable();
            $table->string('expiry_year', 4)->nullable();
            $table->string('cvv', 10)->nullable();
            $table->string('wallet_number', 32)->nullable();
            $table->string('wallet_type', 32)->nullable();
            $table->string('bank_account', 32)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
