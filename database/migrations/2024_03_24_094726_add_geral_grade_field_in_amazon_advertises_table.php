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
            $table->float('geral_grade')->default(0)->after('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amazon_advertises', function (Blueprint $table) {
            $table->dropColumn('geral_grade');
        });
    }
};
