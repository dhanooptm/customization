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
        Schema::create('price_ranges', function (Blueprint $table) {
            $table->id();
            $table->integer('start_point');
            $table->integer('end_point');
            $table->decimal('price',24,2)->default(0);
            $table->foreignId('product_id')->nullable();
            $table->foreignId('seller_id')->nullable();
            $table->boolean('is_endless')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_ranges');
    }
};
