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
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('seller_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->boolean('is_guest')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('order_status')->default('unread');
            $table->decimal('order_amount',23,2);
            $table->json('variation')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('discount',23,2)->default(0.00);
            $table->decimal('tax',23,2)->default(0.00);
            $table->decimal('price_range',23,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};
