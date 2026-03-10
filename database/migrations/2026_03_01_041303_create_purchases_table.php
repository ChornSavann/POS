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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique(); // លេខវិក្កយបត្រ (ឧ៖ PUR-2024-0001)
            $table->date('purchase_date');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained(); // អ្នកបញ្ចូលទិន្នន័យ (Seller/Admin)

            $table->decimal('grand_total', 15, 2)->default(0); // សរុបទឹកប្រាក់ទាំងអស់
            $table->string('status')->default('received'); // status: ordered, received, pending
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
