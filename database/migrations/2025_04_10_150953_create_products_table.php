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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('dummy_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->json('tags')->nullable();
            $table->string('brand');
            $table->string('sku');
            $table->decimal('weight', 8, 2);
            $table->json('dimensions');
            $table->string('warranty_information')->nullable();
            $table->string('shipping_information')->nullable();
            $table->string('availability_status')->nullable();
            $table->json('reviews')->nullable();
            $table->string('return_policy')->nullable();
            $table->integer('minimum_order_quantity')->nullable();
            $table->json('meta')->nullable();
            $table->json('images')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
