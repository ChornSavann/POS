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
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('barcode')->unique()->nullable();
            
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->constrained('brands');
            
            // ភ្ជាប់ទៅកាន់តារាង units របស់បង
            $table->foreignId('unit_id')->constrained('units'); 
            
            $table->decimal('cost', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('alert_qty')->default(10);
            $table->boolean('status')->default(1);
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
