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
        Schema::create('amazon_advertise_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amazon_advertise_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->integer('height');
            $table->integer('width');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_advertise_images');
    }
};
