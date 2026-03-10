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
        Schema::create('seller', function (Blueprint $table) {
            $table->id(); // id
            $table->string('name'); // name
            $table->enum('gender', ['ប្រុស', 'ស្រី', 'ផ្សេងៗ'])->nullable(); // gender (ប្រើ enum សម្រាប់ជម្រើសច្បាស់លាស់)
            $table->string('phone', 20)->nullable(); // phone
            $table->string('email')->unique(); // email (ដាក់ unique ដើម្បីកុំឱ្យជាន់គ្នា)
            $table->text('address')->nullable(); // address
            $table->boolean('status')->default(1); // status (1 = Active, 0 = Inactive)
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller');
    }
};
