<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('method', 50); // CREDIT_CARD, WALLET, BANK_TRANSFER, etc
            $table->string('method_name')->nullable(); // Tên hiển thị
            $table->string('masked_detail')->nullable(); // Thông tin che/mask
            // Thông tin thẻ
            $table->string('card_number', 32)->nullable();
            $table->string('card_holder', 100)->nullable();
            $table->string('expiry_month', 2)->nullable();
            $table->string('expiry_year', 4)->nullable();
            $table->string('cvv', 10)->nullable();
            // Thông tin ví điện tử
            $table->string('wallet_number', 32)->nullable();
            $table->string('wallet_type', 32)->nullable();
            // Thông tin ngân hàng
            $table->string('bank_account', 32)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

