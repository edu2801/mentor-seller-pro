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
        Schema::table('amazon_advertises', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('title');
            $table->longText('bullet_points')->nullable()->after('description');
            $table->text('keywords')->nullable()->after('bullet_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amazon_advertises', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('bullet_points');
            $table->dropColumn('keywords');
        });
    }
};
