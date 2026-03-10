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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            // ភ្ជាប់ទៅតារាង purchases (បើលុប purchase មេ វានឹងលុប items នេះចោលអូតូ)
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');

            $table->foreignId('product_id')->constrained();
            $table->decimal('quantity', 12, 2); // ចំនួនទិញចូល (ប្រើ decimal បើមានលក់ជាគីឡូ)

            $table->decimal('unit_cost', 15, 2);  // តម្លៃដើមទិញចូលក្នុងមួយឯកតា
            $table->decimal('unit_price', 15, 2); // តម្លៃសម្រាប់លក់ចេញដែលកំណត់ពេលទិញចូល

            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2); // (quantity * unit_cost) - discount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
