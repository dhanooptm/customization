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
        Schema::create('product_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('seller_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->decimal('price',24,2)->default(0);
            $table->boolean('status')->default(0);
            $table->integer('quantity');
            $table->text('descriptions')->nullable();
            $table->json('contact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inquiries');
    }
};
