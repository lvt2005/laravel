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
        Schema::create('notification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
            $table->boolean('is_read')->nullable();
            $table->string('message')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->tinyInteger('sent_via')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
