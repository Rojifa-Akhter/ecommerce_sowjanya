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
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->json('image');
            $table->decimal('price', 8, 2); 
            $table->decimal('sale_price', 8, 2); 
            $table->string('quantity');
            $table->string('SKU')->nullable();
            $table->string('stock')->nullable();
            $table->string('tags')->nullable();
            $table->json('color')->nullable();
            $table->string('size')->nullable();
            $table->longText('description');
            $table->unsignedBigInteger('no_of_sale')->default(0)->comment('count total no of sale of this product');
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
