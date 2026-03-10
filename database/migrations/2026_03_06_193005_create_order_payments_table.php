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
        Schema::create('order_payments', function (Blueprint $table) {
           $table->id();

            // បង្កើត Foreign Key ភ្ជាប់ទៅ Table orders
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->dateTime('payment_date')->useCurrent();
            $table->string('payment_method')->default('CASH'); // CASH, ABA, KHQR, etc.

            // ការគ្រប់គ្រងរូបិយប័ណ្ណពីរ (Multi-currency)
            $table->decimal('paid_dollar', 18, 2)->default(0);
            $table->decimal('paid_riel', 18, 2)->default(0);
            $table->decimal('exchange_rate', 18, 2)->default(4100);

            // ចំនួនទឹកប្រាក់សរុប និងសមតុល្យនៅសល់
            $table->decimal('paid_amount', 18, 2);
            $table->decimal('balance_after', 18, 2)->default(0);

            $table->string('payment_status')->default('UNPAID'); // PAID, PARTIAL, UNPAID
            $table->string('payment_ref')->nullable(); // លេខយោងពីធនាគារ (Transaction ID)
            $table->text('note')->nullable();

            $table->timestamps(); // បង្កើត created_at និង updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
