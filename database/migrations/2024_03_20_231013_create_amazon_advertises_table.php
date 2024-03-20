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
        Schema::create('amazon_advertises', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->string('external_sku');
            $table->longText('title');
            $table->string('thumbnail')->nullable();
            $table->string('variation')->nullable();
            $table->string('parent_sku')->nullable();
            $table->string('permalink')->nullable();
            $table->float('price');
            $table->string('sold_quantity')->nullable();
            $table->string('visits')->nullable();
            $table->foreignId('account_id')->constrained('user_marketplaces_accounts')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['external_sku', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amazon_advertises', function (Blueprint $table) {
            $table->dropUnique(['external_sku', 'account_id']);
        });
        Schema::dropIfExists('amazon_advertises');
        
    }
};
