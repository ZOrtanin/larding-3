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
        Schema::table('visits', function (Blueprint $table) {
            $table->string('method', 10)->nullable()->after('ip');
            $table->unsignedSmallInteger('status_code')->nullable()->after('method')->index();
            $table->string('browser')->nullable()->after('user_agent');
            $table->string('platform')->nullable()->after('browser');
            $table->string('device_type')->nullable()->after('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn([
                'method',
                'status_code',
                'browser',
                'platform',
                'device_type',
            ]);
        });
    }
};
