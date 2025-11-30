<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'failed_login_attempts')) {
                $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('login_count');
            }
            if (!Schema::hasColumn('user', 'locked_at')) {
                $table->timestamp('locked_at')->nullable()->after('failed_login_attempts');
            }
            if (!Schema::hasColumn('user', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('locked_at');
            }
            if (!Schema::hasColumn('user', 'last_failed_login_at')) {
                $table->timestamp('last_failed_login_at')->nullable()->after('locked_until');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['failed_login_attempts', 'locked_at', 'locked_until', 'last_failed_login_at']);
        });
    }
};
