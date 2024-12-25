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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); 
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 8, 2);
            $table->enum('status',['delivered','pending'])->default('pending');
            $table->string('street_address')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('contact')->nullable(); 
            $table->string('state')->nullable(); 
            $table->string('zip_code')->nullable(); 
            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
