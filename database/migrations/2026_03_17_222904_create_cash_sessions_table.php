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
        Schema::create('cash_sessions', function (Blueprint $table) {
            $table->id();

            // ព័ត៌មានអ្នកប្រើប្រាស់
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // ពេលវេលា
            $table->dateTime('opening_time');
            $table->dateTime('closing_time')->nullable();

            // ទឹកប្រាក់ (ប្រើ decimal ដើម្បីឱ្យច្បាស់លាស់)
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('system_cash', 15, 2)->default(0);
            $table->decimal('system_bank', 15, 2)->default(0);
            $table->decimal('system_discount', 15, 2)->default(0);
            $table->decimal('actual_cash', 15, 2)->default(0);
            $table->decimal('difference', 15, 2)->default(0);

            // ស្ថានភាព និងចំណាំ
            $table->text('note')->nullable();
            $table->string('status')->default('open'); // 'open' ឬ 'closed'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_sessions');
    }
};
