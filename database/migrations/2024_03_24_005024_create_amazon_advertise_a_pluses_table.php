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
        Schema::create('amazon_advertise_a_pluses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amazon_advertises_item_id');
            $table->text('content_reference_key')->nullable();
            $table->string('content_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_advertise_a_pluses');
    }
};
