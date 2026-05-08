<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->longText('custom_css')->nullable()->after('blade_template');
        });

        Schema::table('block_templates', function (Blueprint $table) {
            $table->longText('custom_css')->nullable()->after('blade_template');
        });
    }

    public function down(): void
    {
        Schema::table('block_templates', function (Blueprint $table) {
            $table->dropColumn('custom_css');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn('custom_css');
        });
    }
};
