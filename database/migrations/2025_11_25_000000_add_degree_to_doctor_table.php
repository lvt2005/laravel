<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor', 'degree')) {
                $table->string('degree')->nullable()->after('description')->comment('Academic degree / title (e.g. Tiến sĩ, Bác sĩ)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctor', function (Blueprint $table) {
            if (Schema::hasColumn('doctor', 'degree')) {
                $table->dropColumn('degree');
            }
        });
    }
};

