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
        Schema::table('orders', function (Blueprint $table) {
            // បន្ថែម Column cash_session_id បន្ទាប់ពី user_id ឬ id
            $table->unsignedBigInteger('cash_session_id')->nullable()->after('id');

            // បើចង់ធ្វើ Foreign Key (Optional ប៉ុន្តែល្អសម្រាប់ Data Integrity)
            // $table->foreign('cash_session_id')->references('id')->on('cash_sessions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('cash_session_id');
        });
    }
};
