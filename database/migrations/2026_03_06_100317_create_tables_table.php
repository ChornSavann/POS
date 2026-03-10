<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // នៅក្នុង file xxxx_xx_xx_xxxxxx_create_tables_table.php
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ឈ្មោះតុ ឧទាហរណ៍៖ Table 01
            $table->text('note')->nullable(); // កំណត់ចំណាំ
            // ស្ថានភាពតុ៖ free = ទំនេរ, busy = មានភ្ញៀវ
            $table->enum('status', ['free', 'busy'])->default('free');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
