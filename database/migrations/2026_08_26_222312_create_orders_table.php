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
            
            // បន្ថែម cash_session_id ត្រង់នេះតែម្ដង
            $table->foreignId('cash_session_id')->nullable()->constrained('cash_sessions')->onDelete('set null');
            
            $table->dateTime('order_date');
            $table->string('invoice_no')->unique();
            $table->foreignId('table_id')->nullable()->constrained('tables');
            $table->foreignId('customer_id')->nullable()->constrained('customers');

            $table->decimal('sub_total', 18, 2);
            $table->decimal('discount', 18, 2)->default(0);
            $table->decimal('total_discount', 18, 2)->default(0);
            $table->decimal('tax', 18, 2)->default(0);
            $table->decimal('grand_total', 18, 2);
            $table->decimal('debt_amount', 18, 2)->default(0); 
            $table->boolean('is_credit')->default(false);
            $table->text('note')->nullable();
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('store_id')->constrained('stores');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_paid')->default(false);
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
