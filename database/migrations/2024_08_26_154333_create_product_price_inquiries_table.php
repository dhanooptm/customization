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
        Schema::create('product_price_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('seller_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->boolean('status')->default(0);
            $table->text('descriptions')->nullable();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->boolean('is_dealer')->default(0);
            $table->boolean('similar_info')->default(0);
            $table->string('pin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_inquiries');
    }
};
