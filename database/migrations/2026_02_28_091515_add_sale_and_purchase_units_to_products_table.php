<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('products', function (Blueprint $table) {
        // បន្ថែម sale_unit_id បន្ទាប់ពី unit_id (Base Unit)
        $table->unsignedBigInteger('sale_unit_id')->nullable()->after('unit_id');
        // បន្ថែម purchase_unit_id បន្ទាប់ពី sale_unit_id
        $table->unsignedBigInteger('purchase_unit_id')->nullable()->after('sale_unit_id');

        
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        
        $table->dropColumn(['sale_unit_id', 'purchase_unit_id']);
    });
}
};
