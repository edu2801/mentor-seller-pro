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
        Schema::create('user_marketplaces_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('marketplace')->default("Amazon");
            $table->string('name');
            $table->string('seller_id');
            $table->float('tax')->default(0);
            $table->longText('token');
            $table->longText('refresh_token');
            $table->dateTime('expiration_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_marketplaces_accounts');
    }
};
