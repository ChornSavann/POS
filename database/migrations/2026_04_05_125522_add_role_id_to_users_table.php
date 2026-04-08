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
        Schema::table('users', function (Blueprint $table) {
            // បន្ថែម role_id បន្ទាប់ពី id ឬ email
            // nullable() ប្រើដើម្បីការពារកុំឱ្យ Error បើមាន User ចាស់ៗក្នុង DB
            $table->foreignId('role_id')
                ->after('id')
                ->nullable()
                ->constrained('roles')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // លុប Foreign Key ចោលវិញពេល Rollback
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
